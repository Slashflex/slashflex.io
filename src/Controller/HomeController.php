<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
}
