<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCommentController extends AbstractController
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Retrieve all comments with nested replies 
     * 
     * @Route("/api/comments", name="api_comment_index", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getComments()
    {
        return $this->json($this->commentRepository->findAll(), 200, [], ['groups' => 'read:comment']);
    }

    /**
     * @Route("/api/comments", name="api_comment_store", methods={"POST"})
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function store(Request $request, SerializerInterface $serializer)
    {
        $manager = $this->getDoctrine()->getManager();

        $jsonRecu = $request->getContent();

        $comment = $serializer->deserialize($jsonRecu, Comment::class, 'json');

        $manager->persist($comment);
        $manager->flush();

        return $this->json($comment, 201, [], ['groups' => 'read:comment']);
    }
}