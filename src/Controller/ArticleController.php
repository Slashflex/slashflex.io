<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $manager;
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }
    /**
     * Shows a single article
     * 
     * @Route("/articles/{slug}", name="single_article")
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'title' => '/FLX | ' . $article->getTitle(),
            'article' => $article
        ]);
    }

    /**
     * Shows all articles
     * 
     * @Route("/articles", name="articles")
     */
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'title' => '/FLX | articles',
            'articles' => $articles
        ]);
    }

    /**
     * Create a new article
     * 
     * @Route("/admin/article/new", name="new_article")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newArticle(Request $request)
    {
        $article = new Article();

        // Define locale 
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        // Concat date and time
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $this->userRepository->findOneBy(['email' => $_ENV['DB_EMAIL']]);

            $article
                ->setUsers($author)
                ->setCreatedAt($currentDate)
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
     * @Route("/admin/article/{slug}/edit", name="edit_article")
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
            'title' => '/FLX | ' . $article->getTitle(),
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * Delete an article
     * 
     * @Route("/admin/article/{slug}/delete", name="delete_article")
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
