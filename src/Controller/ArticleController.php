<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReplyToReply;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\ReplyRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    private $manager;
    private $userRepository;
    private $commentRepository;
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $manager, UserRepository $userRepository, CommentRepository $commentRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Shows a single article
     * 
     * @Route("/blog/post/{slug}", name="single_article")
     */
    public function show(Article $article, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $this->commentRepository->getCommentsForSingleArticle($article);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment
                ->setUsers($this->getUser())
                ->setArticle($article);

            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your comment has been created'
            );

            $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
            return $this->redirect($referer);
        }
        return $this->render('article/show.html.twig', [
            'title' => '/FLX | ' . ucfirst($article->getTitle()),
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Shows all blog's posts
     * 
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'title' => '/FLX | articles',
            'articles' => $articles
        ]);
    }

    /**
     * Create a new article
     * 
     * @Route("/admin/blog/new-post", name="new_article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newArticle(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

            $article
                ->setUsers($author)
                ->initializeSlug();

            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your article has been created'
            );

            return $this->redirectToRoute('admin');
        }

        return $this->render('article/new.html.twig', [
            'title' => '/FLX | New Article',
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit an article
     * 
     * @Route("/admin/blog/post/{slug}/edit", name="edit_article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editArticle(Article $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $title = $request->request->get('article')['title'];
            $article->updateSlug($title);

            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'The article ' . ucfirst($article->getTitle()) . ' has been updated'
            );

            return $this->redirectToRoute('admin', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'title' => '/FLX | ' . ucfirst($article->getTitle()),
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * Delete an article
     * 
     * @Route("/admin/blog/post/{slug}/delete", name="delete_article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteArticle(Article $article)
    {
        $this->manager->remove($article);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'The article ' . ucfirst($article->getTitle()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}
