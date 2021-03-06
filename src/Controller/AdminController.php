<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    private $projectRepository;
    private $userRepository;
    private $articleRepository;

    public function __construct(ProjectRepository $projectRepository, UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
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
            'title' => '/ FLX Admin dashboard',
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
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function adminLogin(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('admin/sign-in.html.twig', [
            'title' => '/ FLX Admin Sign-in',
            'error' => $error
        ]);
    }

    /**
     * Delete a user
     * 
     * @Route("/admin/user/{slug}/delete", name="delete_user")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteUser(User $user)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        $path = 'uploads/avatars/' . $user->getSlug();
        $files = glob('uploads/avatars/' . $user->getSlug() . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        rmdir($path);

        $this->addFlash(
            'success',
            'The user ' . ucfirst($user->getFirstname()) . ' ' . ucfirst($user->getLastname()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}
