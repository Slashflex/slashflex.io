<?php

namespace App\Controller;

use App\Entity\Reply;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReplyController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Delete a given reply
     * 
     * @Route("/reply/{id}/delete", name="delete_reply")
     */
    public function delete(Reply $reply, Request $request)
    {
        $this->manager->remove($reply);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'Your reply has been successfully deleted'
        );

        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        return $this->redirect($referer);
    }
}
