<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * Shows a single project
     * 
     * @Route("/projects/{slug}", name="single_project")
     */
    public function show(Project $project)
    {
        return $this->render('project/show.html.twig', [
            'title' => '/FLX | ' . $project->getTitle(),
            'project' => $project
        ]);
    }

    /**
     * Shows all projects
     * 
     * @Route("/projects", name="projects")
     */
    public function index(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'title' => '/FLX | Projects',
            'projects' => $projects
        ]);
    }
}
