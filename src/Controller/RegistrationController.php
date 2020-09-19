<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(RoleRepository $roleRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = new User();

        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
                ->addRoleUser($role)
                ->initializeSlug();

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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $logo = $_SERVER['DOCUMENT_ROOT'] . '/build/images/email/logo.png';

            // Send an email
            $email = (new TemplatedEmail())
                ->from($_ENV['DB_EMAIL'])
                ->to($user->getEmail())
                ->subject('Thanks for signing up!')
                ->htmlTemplate('emails/welcome.html.twig')
                ->context([
                    'name' => $user->__toString(),
                    'logo' => $logo
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Your account has been successfully created'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => '/FLX | Register'
        ]);
    }
}
