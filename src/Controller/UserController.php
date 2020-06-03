<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $passwordEncoder;
    private $manager;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    /**
     * @Route("/me", name="user")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'title' => '/FLX | Profile',
            'user' => $user
        ]);
    }

    /**
     * User connexion form
     * 
     * @Route("/login", name="user_signin")
     */
    public function userLogin()
    {
        return $this->render('user/sign-in.html.twig', [
            'title' => '/FLX | Login'
        ]);
    }

    /**
     * Edit a user
     * 
     * @Route("/admin/user/{slug}/edit", name="edit_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUsers(User $user, Request $request)
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
                'The profile of ' . ucfirst($user->getFirstname()) . ' ' . ucfirst($user->getLastname()) . ' has been updated'
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
     * User edit form
     * 
     * @Route("/me/{slug}/edit", name="edit_me")
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
                'Your profile has been updated'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit_me.html.twig', [
            'title' => '/FLX | ' . $user->getSlug(),
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
