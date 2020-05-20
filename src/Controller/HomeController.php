<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $arr = [
            'https://i.picsum.photos/id/645/1200/600.jpg',
            'https://i.picsum.photos/id/445/1200/600.jpg',
            'https://i.picsum.photos/id/345/1200/600.jpg',
            'https://i.picsum.photos/id/215/1200/600.jpg',
            'https://i.picsum.photos/id/212/1200/600.jpg',
            'https://i.picsum.photos/id/214/1200/600.jpg',
            'https://i.picsum.photos/id/213/1200/600.jpg',
            'https://i.picsum.photos/id/217/1200/600.jpg',
            'https://i.picsum.photos/id/220/1200/600.jpg',
            'https://i.picsum.photos/id/232/1200/600.jpg',
            'https://i.picsum.photos/id/45/1200/600.jpg'
        ];

        return $this->render('home/index.html.twig', [
            'title' => 'David Saoud | /FLX',
            'images' => $arr
        ]);
    }
}
