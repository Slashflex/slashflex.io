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
     * @Route("/projects", name="show_projects")
     */
    public function index(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'title' => '/FLX | Projects',
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/project/new", name="new_project")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $project = new Project();

        // Define locale 
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        // Concat date and time
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($project->getImage() as $image) {
                $image->addProject($project);
                $entityManager->persist($image);
            }

            $project->setCreatedAt($currentDate);

            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your project has been created'
            );

            return $this->redirectToRoute('show_projects');
        }

        return $this->render('project/new.html.twig', [
            'title' => '/FLX | New Project',
            'form' => $form->createView()
        ]);
    }
}
