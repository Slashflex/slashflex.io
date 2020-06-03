<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    private $projectRepository;
    private $userRepository;
    private $manager;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ProjectRepository $projectRepository, UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
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
        $articles = $this->articleRepository->findAll();
        $admin = $this->getUser();

        return $this->render('admin/index.html.twig', [
            'title' => '/FLX | Admin dashboard',
            'users' => $users,
            'projects' => $projects,
            'articles' => $articles,
            'admin' => $admin
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
     * Delete a user
     * 
     * @Route("/admin/user/{slug}/delete", name="delete_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'The user ' . ucfirst($user->getFirstname()) . ' ' . ucfirst($user->getLastname()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}
