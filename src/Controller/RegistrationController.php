<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Service\MailerService;
use App\Form\ResendTokenFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $userRepository;
    private $mailerService;

    public function __construct(UserRepository $userRepository, MailerService $mailerService)
    {
        $this->userRepository = $userRepository;
        $this->mailerService = $mailerService;
    }

    /**
     * Register a new user and sends an email including a token inside a link
     *
     * @Route("/register", name="app_register")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $manager = $this->getDoctrine()->getManager();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = new User();


        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedToken = $request->request->get('token');

            if ($this->isCsrfTokenValid('register_user', $submittedToken)) {

                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                )->initializeSlug();

                $user->setConfirmationToken($this->generateToken());

                $path = 'uploads/avatars/' . $user->getSlug();
                // Create dedicated folder for the registered user
                $user->setAvatar('avatar.png');

                // Create a user folder with increment when 2 users register having same first and last names
                $count = 0;

                if (file_exists($path)) {
                    mkdir($path . '-' . $count++);
                    copy('uploads/avatars/avatar.png', $path . '/avatar.png');
                } else {
                    mkdir($path);
                    copy('uploads/avatars/avatar.png', $path . '/avatar.png');
                }

                $manager->persist($user);
                $manager->flush();

                $token = $user->getConfirmationToken();
                $email = $request->request->get('registration_form')['email'];
                $firstname = $request->request->get('registration_form')['firstname'];
                $lastname = $request->request->get('registration_form')['lastname'];
                $fullname = ucfirst($firstname) . ' ' . ucfirst($lastname);

                $this->mailerService->sendToken($token, $email, $fullname, 'confirm.html.twig');
                $this->addFlash('success', 'Your account has been successfully created, please check your inbox to confirm your registration');
                return $this->redirectToRoute('home');
            } else {
                return $this->redirectToRoute('internal_server_error');
            }
        }
        return $this->render('registration/register.html.twig', [
            'title' => '/ FLX | Sign up',
            'registrationForm' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Confirm account once user has clicked on link received by email
     *
     * @Route("/account/confirm/{token}", name="confirm_account")
     * @param $token
     * @return Response
     */
    public function confirmAccount($token): Response
    {
        $manager = $this->getDoctrine()->getManager();

        $user = $this->userRepository->findOneBy(['confirmationToken' => $token]);
        if ($user) {
            $user->setConfirmationToken(null);
            $user->setTokenEnabled(true);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'You have successfully confirmed your account. You can now log in'
            );
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Sends a new token to user's input email
     *
     * @Route("/send-confirmation-token", name="send_confirmation_token")
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationToken(Request $request): RedirectResponse
    {
        $manager = $this->getDoctrine()->getManager();

        $email = $request->request->get('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user === null) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('resend_confirmation_token');
        }

        $user->setConfirmationToken($this->generateToken());
        $manager->persist($user);
        $manager->flush();

        $token = $user->getConfirmationToken();
        $email = $user->getEmail();
        $username = $user->getUsername();

        $sendMail = $this->mailerService->sendToken($token, $email, $username, 'confirm.html.twig');
        return $this->redirectToRoute('user_signin', ['sendmail' => $sendMail]);
    }

    /**
     * @Route("/resend-confirmation-token", name="resend_confirmation_token")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function resendConfirmationToken(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $error = $authenticationUtils->getLastAuthenticationError();
        $emailList = $this->userRepository->findAll();

        $form = $this->createForm(ResendTokenFormType::class);
        $form->handleRequest($request);

        $userEmail = [];
        for ($i = 0; $i < count($emailList); $i++) {
            array_push($userEmail, $emailList[$i]->getEmail());
        }

        if ($form->isSubmitted()) {
            $email = $request->request->get('resend_token_form')['email'];

            if (in_array($email, $userEmail)) {
                $user = $this->userRepository->findOneBy(['email' => $email]);
                $user->setConfirmationToken($this->generateToken());
                $user->setTokenEnabled(false);
                $manager->persist($user);
                $manager->flush();
                $token = $user->getConfirmationToken();
                $username = $user->getUsername();

                $sendMail = $this->mailerService->sendToken($token, $email, $username, 'confirm.html.twig');
                $this->addFlash('success', 'An email with a confirmation link has been sent to your inbox');

                return $this->redirectToRoute('home', ['sendmail' => $sendMail]);
            } else {
                $this->addFlash('error', 'There is nobody registered with this email address');
                return $this->render('user/resend_token.html.twig', [
                    'title' => '/ FLX | Sign up confirmation',
                    'form' => $form->createView(),
                    'error' => $error
                ]);
            }
        }

        return $this->render('user/resend_token.html.twig', [
            'title' => '/ FLX | Sign up confirmation',
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @return string
     */
    private function generateToken()
    {
        return mb_strtoupper(strval(bin2hex(openssl_random_pseudo_bytes(16))));
    }
}