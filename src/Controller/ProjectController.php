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
     * @Route("/projects/new", name="new_project")
     */
    public function new(Request $request, EntityManagerInterface $manager)
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
                $manager->persist($image);
            }

            foreach ($project->getContent() as $content) {
                $content->addProject($project);
                $manager->persist($content);
            }

            $project->setCreatedAt($currentDate);
            $project->initializeSlug();

            $manager->persist($project);
            $manager->flush();

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

    /**
     * @Route("/projects/{slug}/editer", name="edit_project")
     */
    public function edit(Project $project, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($project->getImage() as $image) {
                $image->addProject($project);
                $manager->persist($image);
            }

            foreach ($project->getContent() as $content) {
                $content->addProject($project);
                $manager->persist($content);
            }

            // Retrieve updated slug on form submission
            $title = $request->request->get('project')['title'];
            $project->updateSlug($title);

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre project a bien été mise à jour'
            );

            return $this->redirectToRoute('single_project', [
                'slug' => $project->getSlug()
            ]);
        }

        return $this->render('project/edit.html.twig', [
            'title' => '/FLX | ' . $project->getTitle(),
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

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
     * Delete a project
     * 
     * @Route("/projects/{slug}/delete", name="delete_project")
     */
    public function delete(EntityManagerInterface $manager, Project $project)
    {
        $manager->remove($project);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre project a bien été supprimée'
        );

        return $this->redirectToRoute('show_projects');
    }
}
