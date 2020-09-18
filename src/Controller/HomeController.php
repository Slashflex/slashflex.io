<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ContactType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $projectRepository;
    private $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        // Retrieve all projects from database
        $projects = $this->projectRepository->findAll();

        return $this->render('home/index.html.twig', [
            'title' => '/FLX | Home',
            'projects' => $projects
        ]);
    }

    /**
     * Page not found or access denied (non admin)
     * 
     * @Route("/404", name="not_found")
     */
    public function notFound()
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'title' => '/FLX | Page not found',
        ]);
    }

    /**
     * Page not found or access denied (non admin)
     * 
     * @Route("/500", name="internal_server_error")
     */
    public function internalServerError()
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'title' => '/FLX | Internal Server Error',
        ]);
    }

    /**
     * Logout path
     * 
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * About path
     * 
     * @Route("/about-me", name="about")
     */
    public function about()
    {
        // Retrieve my informations from database
        $user = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

        return $this->render('user/about.html.twig', [
            'title' => '/FLX | About me',
            'user' => $user
        ]);
    }

    /**
     * Contact page
     * 
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class, null);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $request->request->get('contact')['firstname'];
            $lastname = $request->request->get('contact')['lastname'];
            $mail = $request->request->get('contact')['email'];
            $subject = $request->request->get('contact')['subject'];

            $admin = $_ENV['DB_FIRSTNAME'] . ' ' . $_ENV['DB_LASTNAME'];
            $date = new DateTime('NOW');


            // Send an email
            $eMail = (new TemplatedEmail())
                ->from($_ENV['DB_EMAIL'])
                ->to($_ENV['DB_EMAIL'])
                ->subject($subject)
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'name' => $admin,
                    'date' => "{$date->format('\o\n l jS F Y')} at {$date->format('H:i')}",
                    'user' => $firstname . ' ' . $lastname,
                    'subject' => $subject,
                    'mail' => $mail
                ]);

            $mailer->send($eMail);

            $this->addFlash(
                'success',
                'Your message has been sent.'
            );

            return $this->redirectToRoute('home');
        }

        $projects = $this->projectRepository->findAll();

        // Retrieve my informations from database
        $user = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

        return $this->render('user/contact.html.twig', [
            'title' => '/FLX | Contact me',
            'user' => $user,
            'form' => $form->createView(),
            'projects' => $projects
        ]);
    }

    /**
     * Terms of use path
     * 
     * @Route("/terms-of-use", name="terms")
     */
    public function terms()
    {
        return $this->render('user/terms.html.twig', [
            'title' => '/FLX | Terms of use'
        ]);
    }
}
