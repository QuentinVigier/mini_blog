<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/articles')] 
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_all', methods: ['GET'])]
    public function index(ArticleRepository $ar): Response
    {
        $allArticles = $ar->findAll();
        return $this->render('article/all.html.twig', [
            'allArticles' => $allArticles,
        ]);
    }

    #[Route('/{title}', name: 'app_article_show', methods: ['GET'])]
    public function show(string $title, ArticleRepository $ar): Response
    {
        $article = $ar->findOneBy(['title' => $title]);

        if (!$article) {
            throw $this->createNotFoundException('L\'article demandé n\'existe pas.');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}