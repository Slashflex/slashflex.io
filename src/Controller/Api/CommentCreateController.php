<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Security;

class CommentCreateController
{
    private $security;

    /**
     * CommentCreateController constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Comment $data
     * @return Comment
     */
    public function __invoke(Comment $data)
    {
        $data->setUsers($this->security->getUser());
        return $data;
    }
}
