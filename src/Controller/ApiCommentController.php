<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCommentController extends AbstractController
{
    private $commentRepository;
    private $manager;

    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $manager)
    {
        $this->commentRepository = $commentRepository;
        $this->manager = $manager;
    }

    /**
     * Retrieve all comments with nested replies 
     * 
     * @Route("/api/comments", name="api_comment_index", methods={"GET"})
     */
    public function getComments()
    {
        return $this->json($this->commentRepository->findAll(), 200, [], ['groups' => 'read:comment']);
    }

    /**
     * @Route("/api/comments", name="api_comment_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer)
    {
        $jsonRecu = $request->getContent();

        $comment = $serializer->deserialize($jsonRecu, Comment::class, 'json');

        $this->manager->persist($comment);
        $this->manager->flush();

        return $this->json($comment, 201, [], ['groups' => 'read:comment']);
    }
}
