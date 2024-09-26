<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(ArticleRepository $ar): Response
    {
        $lastArticles = $ar->findBy([], ['createdAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'lastArticles' => $lastArticles,
            'totalArticles' => count($ar->findAll())
        ]);
    }
}
