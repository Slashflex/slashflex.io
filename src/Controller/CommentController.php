<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * Delete a given comment
     * 
     * @Route("/comment/{id}/delete", name="delete_comment")
     *
     * @param Comment $comment
     * @param Request $request
     * @return void
     */
    public function delete(Comment $comment, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            'Your comment has been successfully deleted'
        );

        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        return $this->redirect($referer);
    }
}