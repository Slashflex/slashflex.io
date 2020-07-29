<?php

namespace App\Controller;

use App\Entity\Reply;
use App\Entity\Comment;
use App\Form\ReplyType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Delete a given comment
     * 
     * @Route("/comment/{id}/delete", name="delete_comment")
     */
    public function delete(Comment $comment, Request $request)
    {
        $this->manager->remove($comment);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'Your comment has been successfully deleted'
        );

        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        return $this->redirect($referer);
    }
}
