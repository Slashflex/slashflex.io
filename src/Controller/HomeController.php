<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository, RoleRepository $roleRepository)
    {
        $this->projectRepository = $projectRepository;
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
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig', [
            'title' => '/FLX | Page not found',
        ]);
    }
}
