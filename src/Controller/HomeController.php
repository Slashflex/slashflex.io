<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
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
            'title' => '/FLX | About',
            'user' => $user
        ]);
    }
}
