<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use Cocur\Slugify\Slugify;
use App\Form\AvatarUploadType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
    public function userLogin(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('user/sign-in.html.twig', [
            'title' => '/FLX | Login',
            'error' => $error
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
    public function editUser(User $user, Request $request, SluggerInterface $slugger)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Store current user path
        $oldName = 'uploads/avatars/' . $user->getSlug() . '/';

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve user first and last names on form submission
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $fullname = $slugger->slug($firstname . '-' . $lastname);

            // Hash new password and update slug on form submission
            $hash = $this->passwordEncoder->encodePassword($user, $request->request->get('user')['password']);
            $user
                ->setPassword($hash)
                ->updateSlug();
            $user->setAvatar($user->getAvatar());

            // New user path
            $newName = 'uploads/avatars/' . $fullname . '/';

            // Rename old user folder with new first and last names from submission
            rename($oldName, $newName);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your profile has been updated'
            );

            return $this->redirectToRoute('user');
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
        $form = $this->createForm(AvatarUploadType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatar')->getData();

            // this condition is needed because the 'avatar' field is not required
            // so the Avatar file must be processed only when a file is uploaded
            if ($this->getUser()->getAvatar() != 'avatar.png') {
                $user->setAvatar('avatar.png');
            }

            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . $user->getId() . '.' . $avatarFile->guessExtension();

                $fileSystem = new Filesystem();
                // Move the file to the directory where brochures are stored
                try {
                    $slug = new Slugify();
                    // Retrieve document root
                    $avatarDir = $_SERVER["DOCUMENT_ROOT"] . '/uploads/avatars/';

                    // Create folder based on user's firtname and lastname
                    $userDir = $avatarDir . $slug->slugify($this->getUser()->__toString());

                    // Remove old avatar ffrom user folder
                    $fileSystem->remove([$userDir . '/', $user->getAvatar()]);

                    if (!file_exists($userDir) || file_exists($userDir)) {
                        $userFolder = $userDir;
                        // Move the uploaded file to the current user avatar folder
                        $avatarFile->move(
                            $userFolder,
                            $newFilename
                        );
                    }
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // Updates the 'avatar' property to store the file name instead of its contents
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
