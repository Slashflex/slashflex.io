<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    private $projectRepository;
    private $userRepository;
    private $roleRepository;
    private $passwordEncoder;
    private $manager;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ProjectRepository $projectRepository, UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        $projects = $this->projectRepository->findAll();

        // foreach ($projects->getImage() as $image) {
        // dd($projects->getImage());
        // return $image;
        // }

        return $this->render('admin/index.html.twig', [
            'title' => '/FLX | Admin dashboard',
            'users' => $users,
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin/sign-in", name="admin_signin")
     */
    public function adminSignIn()
    {
        return $this->render('admin/sign-in.html.twig', [
            'title' => '/FLX | Admin Sign-in',
        ]);
    }

    /**
     * @Route("/admin/sign-up", name="admin_signup")
     */
    public function adminSignUp(Request $request)
    {
        $admin = new User();

        // Define locale 
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        // Concat date and time
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

        $form = $this->createForm(UserType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->passwordEncoder->encodePassword($admin, $admin->getPassword());

            $admin
                ->setCreatedAt($currentDate)
                ->setPassword($hash)
                ->initializeSlug();

            $this->manager->persist($admin);
            $this->manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/sign-up.html.twig', [
            'title' => '/FLX | Admin Sign-up',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/admin/project/new", name="new_project")
     */
    public function newProject(Request $request, EntityManagerInterface $manager)
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

            return $this->redirectToRoute('admin');
        }

        return $this->render('project/new.html.twig', [
            'title' => '/FLX | New Project',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{slug}/edit", name="edit_user")
     */
    public function editUser(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $hash = $this->passwordEncoder->encodePassword($user, $request->request->get('user')['password']);
            $user
                ->setPassword($hash)
                ->updateSlug();

            $manager->persist($user);
            $manager->flush();

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
     * @Route("/admin/project/{slug}/edit", name="edit_project")
     */
    public function editProject(Project $project, Request $request, EntityManagerInterface $manager)
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
     */
    public function delete(EntityManagerInterface $manager, Project $project)
    {
        $manager->remove($project);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre project a bien été supprimée'
        );

        return $this->redirectToRoute('admin');
    }
}
