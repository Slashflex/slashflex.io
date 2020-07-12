<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(RoleRepository $roleRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        // Define locale 
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        // Concat date and time
        $currentDate = 'The '  . strftime("%A %d %B %Y") . ' at ' . strftime("%H:%M");

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                ->setAvatar('avatar.png')
                ->addRoleUser($role)
                ->setCreatedAt($currentDate)
                ->initializeSlug();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => '/FLX | Register'
        ]);
    }
}
