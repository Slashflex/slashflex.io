<?php

namespace App\Controller;

use App\Entity\Reply;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiReplyController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/api/reply", name="api_post_index", methods={"GET"})
     */
    public function getPost(ReplyRepository $replyRepository)
    {
        return $this->json($replyRepository->findAll(), 200, [], ['groups' => 'reply:read']);
    }

    /**
     * @Route("/api/reply", name="api_post_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer)
    {
        $jsonRecu = $request->getContent();

        $reply = $serializer->deserialize($jsonRecu, Reply::class, 'json');
        // dd($reply);
        $reply->setCreatedAt(new \DateTime());

        $this->manager->persist($reply);
        $this->manager->flush();

        return $this->json($reply, 201, [], ['groups' => 'reply:read']);
    }
}
