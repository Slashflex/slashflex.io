<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PDFController extends AbstractController
{

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Generates a pdf document out of an post
     * 
     * @Route("/blog/post/{slug}/pdf", name="article_to_pdf")
     *
     * @param Pdf $snappy
     * @param Article $article
     * @return void
     */
    public function indexAction(Pdf $snappy, Article $article)
    {
        $slug = $this->articleRepository->findOneBy(["slug" => $article->getSlug()]);
        $url = 'https://slashflex.io/blog/post/' . $slug->__toString();

        // WkHtmlToPdf options => wkhtmltopdf -H
        $options = [
            'disable-javascript' => true,
            'margin-top'    => 10,
            'margin-right'  => 15,
            'margin-bottom' => 15,
            'margin-left'   => 15,
        ];

        return new Response($snappy->getOutput($url, $options), 200, array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'inline; filename="' . $slug . '.pdf"'
        ));
    }
}