<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    private $projectRepository;
    private $userRepository;
    private $passwordEncoder;
    private $manager;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ProjectRepository $projectRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    /**
     * Admin dashboard
     * 
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        $projects = $this->projectRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'title' => '/FLX | Admin dashboard',
            'users' => $users,
            'projects' => $projects
        ]);
    }

    /**
     * Admin connexion form
     * 
     * @Route("/admin/sign-in", name="admin_signin")
     */
    public function adminLogin()
    {
        return $this->render('admin/sign-in.html.twig', [
            'title' => '/FLX | Admin Sign-in',
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

            foreach ($project->getImage() as $image) {
                $image->addProject($project);
                $this->manager->persist($image);
            }

            foreach ($project->getContent() as $content) {
                $content->addProject($project);
                $this->manager->persist($content);
            }

            $project->setCreatedAt($currentDate);
            $project->initializeSlug();

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
     * Edit a user
     * 
     * @Route("/admin/user/{slug}/edit", name="edit_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $hash = $this->passwordEncoder->encodePassword($user, $request->request->get('user')['password']);
            $user
                ->setPassword($hash)
                ->updateSlug();

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Votre user a bien été mise à jour'
            );

            return $this->redirectToRoute('admin', [
                'slug' => $user->getSlug()
            ]);
        }

        return $this->render('admin/edit_user.html.twig', [
            'title' => '/FLX | ' . $user->getSlug(),
            'form' => $form->createView(),
            'user' => $user
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
            foreach ($project->getImage() as $image) {
                $image->addProject($project);
                $this->manager->persist($image);
            }

            foreach ($project->getContent() as $content) {
                $content->addProject($project);
                $this->manager->persist($content);
            }

            // Retrieve updated slug on form submission
            $title = $request->request->get('project')['title'];
            $project->updateSlug($title);

            $this->manager->persist($project);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Votre project a bien été mise à jour'
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
    public function delete(Project $project)
    {
        $this->manager->remove($project);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'Votre project a bien été supprimée'
        );

        return $this->redirectToRoute('admin');
    }

    /**
     * Logout path
     * 
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
