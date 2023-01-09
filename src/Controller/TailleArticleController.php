<?php

namespace App\Controller;

use App\Entity\TailleArticle;
use App\Form\TailleArticleType;
use App\Repository\TailleArticleRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taille/article')]

class TailleArticleController extends  AbstractDashboardController
{
    #[Route('/', name: 'app_taille_article_index', methods: ['GET'])]
    public function indexX(TailleArticleRepository $tailleArticleRepository): Response
    {
        return $this->render('taille_article/index.html.twig', [
            'taille_articles' => $tailleArticleRepository->findAll(),
        ]);
    }
   
    #[Route('/new', name: 'app_taille_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TailleArticleRepository $tailleArticleRepository): Response
    {
        $tailleArticle = new TailleArticle();
        $form = $this->createForm(TailleArticleType::class, $tailleArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleArticleRepository->save($tailleArticle, true);

            return $this->redirectToRoute('app_taille_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_article/new.html.twig', [
            'taille_article' => $tailleArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{articls}', name: 'app_taille_article_show', methods: ['GET'])]
    public function show(TailleArticle $tailleArticle): Response
    {
        return $this->render('taille_article/show.html.twig', [
            'taille_article' => $tailleArticle,
        ]);
    }

    #[Route('/{articls}/edit', name: 'app_taille_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TailleArticle $tailleArticle, TailleArticleRepository $tailleArticleRepository): Response
    {
        $form = $this->createForm(TailleArticleType::class, $tailleArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tailleArticleRepository->save($tailleArticle, true);

            return $this->redirectToRoute('app_taille_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('taille_article/edit.html.twig', [
            'taille_article' => $tailleArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{articls}', name: 'app_taille_article_delete', methods: ['POST'])]
    public function delete(Request $request, TailleArticle $tailleArticle, TailleArticleRepository $tailleArticleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tailleArticle->getArticls(), $request->request->get('_token'))) {
            $tailleArticleRepository->remove($tailleArticle, true);
        }

        return $this->redirectToRoute('app_taille_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
