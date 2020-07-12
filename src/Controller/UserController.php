<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $userRepository;
    private $passwordEncoder;
    private $manager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
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
                'The profile of ' . $user->__ToString() . ' has been updated'
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

    /**
     * Add an avatar to user's profile
     * 
     * @Route("/me/{slug}/upload", name="upload_avatar")
     */
    public function addUserAvatar(User $user, Request $request, SluggerInterface $slugger)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatar')->getData();

            // this condition is needed because the 'avatar' field is not required
            // so the Avatar file must be processed only when a file is uploaded
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . $user->getId() . '.' . $avatarFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'avatar' property to store the file name
                // instead of its contents
                $user->setAvatar($newFilename);
            }

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your avatar has been uploaded'
            );

            return $this->redirectToRoute('user');
        }

        return $this->render('user/upload.html.twig', [
            'title' => '/FLX | ' . $user->__toString(),
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
