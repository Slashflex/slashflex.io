<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    private $manager;
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
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

    /**
     * Create a new project
     * 
     * @Route("/admin/project/new", name="new_project")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newProject(Request $request)
    {
        $project = new Project();

        // Define locale 
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        // Concat date and time
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

            $project
                ->setUsers($author)
                ->setCreatedAt($currentDate)
                ->initializeSlug();

            $this->manager->persist($project);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your project has been created'
            );

            return $this->redirectToRoute('admin');
        }

        return $this->render('project/new.html.twig', [
            'title' => '/FLX | New Project',
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit a project
     * 
     * @Route("/admin/project/{slug}/edit", name="edit_project")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editProject(Project $project, Request $request)
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $title = $request->request->get('project')['title'];

            $project->updateSlug($title);

            $this->manager->persist($project);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'The project ' . ucfirst($project->getTitle()) . ' has been updated'
            );

            return $this->redirectToRoute('admin', [
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
     * Delete a project
     * 
     * @Route("/admin/project/{slug}/delete", name="delete_project")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteProject(Project $project)
    {
        $this->manager->remove($project);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'The project ' . ucfirst($project->getTitle()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}
