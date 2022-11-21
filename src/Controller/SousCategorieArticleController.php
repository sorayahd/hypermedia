<?php

namespace App\Controller;

use App\Entity\SousCategorieArticle;
use App\Form\SousCategorieArticleType;
use App\Repository\SousCategorieArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin', name:'admin_')]
class SousCategorieArticleController extends AbstractController
{
    #[Route('/index', name: 'app_sous_categorie_article_index', methods: ['GET'])]
    public function index(SousCategorieArticleRepository $sousCategorieArticleRepository): Response
    {
        return $this->render('sous_categorie_article/index.html.twig', [
            'sous_categorie_articles' => $sousCategorieArticleRepository->findAll(),
        ]);
    }

    #[Route('/sousCategorie/new', name: 'app_sous_categorie_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SousCategorieArticleRepository $sousCategorieArticleRepository): Response
    {
        $sousCategorieArticle = new SousCategorieArticle();
        $form = $this->createForm(SousCategorieArticleType::class, $sousCategorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sousCategorieArticleRepository->save($sousCategorieArticle, true);

            return $this->redirectToRoute('admin_app_sous_categorie_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie_article/new.html.twig', [
            'sous_categorie_article' => $sousCategorieArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_article_show', methods: ['GET'])]
    public function show(SousCategorieArticle $sousCategorieArticle): Response
    {
        return $this->render('sous_categorie_article/show.html.twig', [
            'sous_categorie_article' => $sousCategorieArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sous_categorie_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousCategorieArticle $sousCategorieArticle, SousCategorieArticleRepository $sousCategorieArticleRepository): Response
    {
        $form = $this->createForm(SousCategorieArticleType::class, $sousCategorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sousCategorieArticleRepository->save($sousCategorieArticle, true);

            return $this->redirectToRoute('admin_app_sous_categorie_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie_article/edit.html.twig', [
            'sous_categorie_article' => $sousCategorieArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_article_delete', methods: ['POST'])]
    public function delete(Request $request, SousCategorieArticle $sousCategorieArticle, SousCategorieArticleRepository $sousCategorieArticleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sousCategorieArticle->getId(), $request->request->get('_token'))) {
            $sousCategorieArticleRepository->remove($sousCategorieArticle, true);
        }

        return $this->redirectToRoute('admin_app_sous_categorie_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
