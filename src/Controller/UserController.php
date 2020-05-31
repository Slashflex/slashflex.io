<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
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
}
