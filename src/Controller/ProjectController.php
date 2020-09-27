<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    private $projectRepository;
    private $userRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * Shows a single project
     * 
     * @Route("/works/{slug}", name="single_project")
     *
     * @param Project $project
     * @return void
     */
    public function show(Project $project)
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('project/show.html.twig', [
            'title' => '/FLX | ' . ucfirst($project->getTitle()),
            'project' => $project,
            'projects' => $projects
        ]);
    }

    /**
     * Shows all work
     * 
     * @Route("/works", name="works")
     *
     * @return void
     */
    public function index()
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'title' => '/FLX | Works',
            'projects' => $projects
        ]);
    }

    /**
     * Create a new project
     * 
     * @Route("/admin/works/new", name="new_project")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     * @return void
     */
    public function newProject(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

            foreach ($project->getAttachments() as $image) {
                $image->setproject($project);
                $this->manager->persist($image);
            }

            $project
                ->setUsers($author)
                ->initializeSlug();

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your project has been created'
            );

            return $this->redirectToRoute('admin');
        }

        return $this->render('project/new.html.twig', [
            'title' => '/FLX | New work',
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit a project
     * 
     * @Route("/admin/works/{slug}/edit", name="edit_project")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Project $project
     * @param Request $request
     * @return void
     */
    public function editProject(Project $project, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $title = $request->request->get('project')['title'];

            $project->updateSlug($title);

            foreach ($project->getAttachments() as $image) {
                $image->setproject($project);
                $this->manager->persist($image);
            }

            $manager->persist($project);
            $manager->flush();

            $this->addFlash(
                'success',
                'The work ' . ucfirst($project->getTitle()) . ' has been updated'
            );

            return $this->redirectToRoute('admin', [
                'slug' => $project->getSlug()
            ]);
        }

        return $this->render('project/edit.html.twig', [
            'title' => '/FLX | ' . ucfirst($project->getTitle()),
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * Delete a project
     * 
     * @Route("/admin/works/{slug}/delete", name="delete_project")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Project $project
     * @return void
     */
    public function deleteProject(Project $project)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($project);
        $manager->flush();

        $this->addFlash(
            'success',
            'The work ' . ucfirst($project->getTitle()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}