<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $userRepository;
    private $commentRepository;
    private $articleRepository;

    /**
     * Constructor
     *
     * @param ArticleRepository $articleRepository
     * @param UserRepository $userRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, CommentRepository $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Shows a single article
     *
     * @Route("/blog/post/{slug}", name="single_article")
     *
     * @param Article $article
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function show(Article $article, Request $request)
    {
        $manager = $this->getDoctrine()->getMAnager();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comments = $this->commentRepository->getCommentsForSingleArticle($article);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment
                ->setUsers($this->getUser())
                ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your comment has been created'
            );

            $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
            return $this->redirect($referer);
        }
        return $this->render('article/show.html.twig', [
            'title' => '/ FLX ' . ucfirst($article->getTitle()),
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * Shows all blog's posts
     *
     * @Route("/blog", name="blog")
     *
     * @return Response
     */
    public function index()
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'title' => '/ FLX Blog',
            'articles' => $articles
        ]);
    }

    /**
     * Create a new article
     *
     * @Route("/admin/blog/new-post", name="new_article")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newArticle(Request $request)
    {
        $manager = $this->getDoctrine()->getMAnager();

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

            $article
                ->setUsers($author)
                ->initializeSlug();

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your article has been created'
            );

            return $this->redirectToRoute('admin');
        }

        return $this->render('article/new.html.twig', [
            'title' => '/ FLX New Article',
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit an article
     *
     * @Route("/admin/blog/post/{slug}/edit", name="edit_article")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Article $article
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editArticle(Article $article, Request $request)
    {
        $manager = $this->getDoctrine()->getMAnager();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve updated slug on form submission
            $article->updateSlug();

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'The article ' . ucfirst($article->getTitle()) . ' has been updated'
            );

            return $this->redirectToRoute('admin', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'title' => '/ FLX ' . ucfirst($article->getTitle()),
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * Delete an article
     *
     * @Route("/admin/blog/post/{slug}/delete", name="delete_article")
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Article $article
     * @return RedirectResponse
     */
    public function deleteArticle(Article $article)
    {
        $manager = $this->getDoctrine()->getMAnager();

        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            'The article ' . ucfirst($article->getTitle()) . ' has been deleted'
        );

        return $this->redirectToRoute('admin');
    }
}
