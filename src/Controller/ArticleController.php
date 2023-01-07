<?php

namespace App\Controller;



use App\Entity\Article;
use App\Entity\User;
use App\Form\Article1Type;
use App\Repository\ArticleRepository;
use App\Repository\CategorieArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FileUploadError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
 
    #[Route('/accueil', name: 'app_accueil')]
    public function indexAcceuil(SessionInterface $session,ArticleRepository $articleRepository): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,SessionInterface $session): Response
    {



        // $panier = $session->get('panier', []);
        // $total = 0;
        // $quantityTotal = 0;

        // $panierWithData = [];

        // foreach($panier as $id => $quantity) {
        //     $panierWithData[]= [
        //         'product'=>$articleRepository->find($id),
        //         'quantity'=> $quantity
               
        //     ];
        //     //$total += $quantity * $product->get/100;
        //      //$total += $articleRepository->find($id)->getPrix() * $quantity;
        //     $total += 1;
        //     $quantityTotal += $quantity;
            
        // }
      
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            // 'items'=>$panierWithData,
            // 'total'=>$total,
            // 'quantity'=>$quantityTotal
        ]);
    }
    
   
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository,SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setImage($newFilename);
            }


             

            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

 //----------------------------ARTICLE PAR GENRE----------------------

    /**
     * @Route("/genre/homme", name="app_article_homme_show")
     * 
     */

    public function ArticleHomme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy(['sexe' => 1]);
      
        return $this->render('article/ArticleHomme.html.twig', [
            'articles' => $articles,
            
        ]);
       
    }

     /**
     * @Route("/genre/femme", name="app_article_femme_show")
     * 
     */

     public function ArticleFemme( ArticleRepository $repository ): Response
     {
 
         $articles =$repository->findBy(['sexe' => 2]);
 
         return $this->render('article/ArticleFemme.html.twig', [
             'articles' => $articles,
         ]);
     }
 



     //---------------------------------Article par categorie(jeans)--------------

      /**
     * @Route("/jeans/homme", name="app_article_jeans_homme")
     * 
     */
    public function JeansHomme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 1,
            'categorie'=> 1
        ]);

        return $this->render('article/Jeans/JeansHomme.html.twig', [
            'articles' => $articles,
        ]);
    }
 
      /**
     * @Route("/jeans/femme", name="app_article_jeans_femme")
     * 
     */
    public function JeansFemme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 2,
            'categorie'=> 1
    ]);

        return $this->render('article/Jeans/JeansFemme.html.twig', [
            'articles' => $articles,
        ]);
    }





        //---------------------------------Article par categorie(Tshirt)--------------

      /**
     * @Route("/tshirt/homme", name="app_article_tshirt_homme")
     * 
     */
    public function TshirtHomme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 1,
            'categorie'=> 2
        ]);

        return $this->render('article/T-shirt/tshirtHomme.html.twig', [
            'articles' => $articles,
        ]);
    }
 
      /**
     * @Route("/tshirt/femme", name="app_article_tshirt_femme")
     * 
     */
    public function TshirtFemme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 2,
            'categorie'=> 2
    ]);

        return $this->render('article/T-shirt/tshirtFemme.html.twig', [
            'articles' => $articles,
        ]);
    }

//----------------------Chaussure------------------------------

  /**
     * @Route("/chaussure/homme", name="app_article_chaussure_homme")
     * 
     */
    public function chaussureHomme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 1,
            'categorie'=> 3
        ]);

        return $this->render('article/T-shirt/tshirtHomme.html.twig', [
            'articles' => $articles,
        ]);
    }
 
      /**
     * @Route("/tshirt/femme", name="app_article_chaussure_femme")
     * 
     */
    public function chaussureFemme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 2,
            'categorie'=> 3
    ]);

        return $this->render('article/T-shirt/tshirtFemme.html.twig', [
            'articles' => $articles,
        ]);
    }




    //-----------------------------------------------PROMO--------------------------

    /**
     * @Route("/promotion/promotion", name="app_article_promotion")
     * 
     */
    public function promotion( ArticleRepository $repository ): Response
    {

        $articles = $repository->ArticleEnPromotion(0);

        return $this->render('article/Promo/promotion.html.twig', [
            'articles' => $articles,
        ]);
    }
 
  


     



    //-----------------------------------------------Favoris--------------------------
    
    /**
     * @Route("/favoris/ajout/{id}", name="app_ajout_favoris")
     */
    public function ajoutFavoris(Article $article,ManagerRegistry $doctrine,)
    {
        if(!$article){
            throw new NotFoundHttpException('Pas d\'article trouvée');
        }
        $entityManager = $doctrine->getManager();
        $article->addFavori($this->getUser());
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('app_article_index');
    }

    /**
     * @Route("/favoris/retrait/{id}", name="app_retrait_favoris")
     */
   public function retraitFavoris(Article $article,ManagerRegistry $doctrine,)
    {
        if(!$article){
            throw new NotFoundHttpException('Pas d\'article trouvée');
        }
        $entityManager = $doctrine->getManager();
        $article->removeFavori($this->getUser());
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('app_article_index');
    }

     /**
     * @Route("/favoris/index", name="app_index_favoris")
     */
    public function Favoris(ArticleRepository $articleRepository): Response
    {
    
        //$favoris = $this->$user->getFavoris();
        return $this->render('Favoris/index.html.twig', [
            'favoris' => $articleRepository->findAll(),
        ]);
    }









}
// use App\Entity\Article;
// use App\Entity\User;
// use App\Form\Article1Type;
// use App\Repository\ArticleRepository;
// use App\Repository\CategorieArticleRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Form\FileUploadError;
// use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\String\Slugger\SluggerInterface;
// use Doctrine\Persistence\ManagerRegistry;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;

// #[Route('/article')]
// class ArticleController extends AbstractController
// {
 
//     #[Route('/accueil', name: 'app_accueil')]
//     public function indexAcceuil(SessionInterface $session,ArticleRepository $articleRepository): Response
//     {
//         return $this->render('accueil/index.html.twig', [
//             'controller_name' => 'ArticleController',
//         ]);
//     }
    
//     #[Route('/', name: 'app_article_index', methods: ['GET'])]
    // public function index(ArticleRepository $articleRepository,SessionInterface $session): Response
    // {



    //     $panier = $session->get('panier', []);
    //     $total = 0;
    //     $quantityTotal = 0;

    //     $panierWithData = [];

    //     foreach($panier as $id => $quantity) {
    //         $panierWithData[]= [
    //             'product'=>$articleRepository->find(19),
    //             'quantity'=> $quantity
               
    //         ];

    //         $total += $articleRepository->find($id)->getPrix() * $quantity;
    //         $quantityTotal += $quantity;
            
    //     }
      
    //     return $this->render('article/index.html.twig', [
    //         'articles' => $articleRepository->findAll(),
    //         'items'=>$panierWithData,
    //         'total'=>$total,
    //         'quantity'=>$quantityTotal
    //     ]);
    // }
    
   
    // #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, ArticleRepository $articleRepository,SluggerInterface $slugger): Response
    // {
    //     $article = new Article();
    //     $form = $this->createForm(Article1Type::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $brochureFile = $form->get('image')->getData();

    //         // this condition is needed because the 'brochure' field is not required
    //         // so the PDF file must be processed only when a file is uploaded
    //         if ($brochureFile) {
    //             $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
    //             // this is needed to safely include the file name as part of the URL
    //             $safeFilename = $slugger->slug($originalFilename);
    //             $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

    //             // Move the file to the directory where brochures are stored
    //             try {
    //                 $brochureFile->move(
    //                     $this->getParameter('uploads'),
    //                     $newFilename
    //                 );
    //             } catch (FileException $e) {
    //                 // ... handle exception if something happens during file upload
    //             }

    //             // updates the 'brochureFilename' property to store the PDF file name
    //             // instead of its contents
    //             $article->setImage($newFilename);
    //         }


             

    //         $articleRepository->save($article, true);

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('article/new.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }




//     #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
//     public function show(Article $article): Response
//     {
//         return $this->render('article/show.html.twig', [
//             'article' => $article,
//         ]);
//     }

    
//     #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
//     public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
//     {
//         $form = $this->createForm(Article1Type::class, $article);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $articleRepository->save($article, true);

//             return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
//         }

//         return $this->renderForm('article/edit.html.twig', [
//             'article' => $article,
//             'form' => $form,
//         ]);
//     }

//     #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
//     public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
//     {
//         if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
//             $articleRepository->remove($article, true);
//         }

//         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
//     }

//  //----------------------------ARTICLE PAR GENRE----------------------

//     /**
//      * @Route("/genre/homme", name="app_article_homme_show")
//      * 
//      */

//     public function ArticleHomme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy(['genre' => 'Homme']);

//         return $this->render('article/ArticleHomme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }

//      /**
//      * @Route("/genre/femme", name="app_article_femme_show")
//      * 
//      */

//      public function ArticleFemme( ArticleRepository $repository ): Response
//      {
 
//          $articles =$repository->findBy(['genre' => 'Femme']);
 
//          return $this->render('article/ArticleFemme.html.twig', [
//              'articles' => $articles,
//          ]);
//      }
 



//      //---------------------------------Article par categorie(jeans)--------------

//       /**
//      * @Route("/jeans/homme", name="app_article_jeans_homme")
//      * 
//      */
//     public function JeansHomme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Homme',
//             'categorie'=> 2
//         ]);

//         return $this->render('article/Jeans/JeansHomme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }
 
//       /**
//      * @Route("/jeans/femme", name="app_article_jeans_femme")
//      * 
//      */
//     public function JeansFemme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Femme',
//             'categorie'=> 2
//     ]);

//         return $this->render('article/Jeans/JeansFemme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }





//         //---------------------------------Article par categorie(Tshirt)--------------

//       /**
//      * @Route("/tshirt/homme", name="app_article_tshirt_homme")
//      * 
//      */
//     public function TshirtHomme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Homme',
//             'categorie'=> 1
//         ]);

//         return $this->render('article/T-shirt/tshirtHomme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }
 
//       /**
//      * @Route("/tshirt/femme", name="app_article_tshirt_femme")
//      * 
//      */
//     public function TshirtFemme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Femme',
//             'categorie'=> 1
//     ]);

//         return $this->render('article/T-shirt/tshirtFemme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }

// //----------------------Chaussure------------------------------

//   /**
//      * @Route("/chaussure/homme", name="app_article_chaussure_homme")
//      * 
//      */
//     public function chaussureHomme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Homme',
//             'categorie'=> 5
//         ]);

//         return $this->render('article/T-shirt/tshirtHomme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }
 
//       /**
//      * @Route("/tshirt/femme", name="app_article_chaussure_femme")
//      * 
//      */
//     public function chaussureFemme( ArticleRepository $repository ): Response
//     {

//         $articles =$repository->findBy([
//             'genre' => 'Femme',
//             'categorie'=> 5
//     ]);

//         return $this->render('article/T-shirt/tshirtFemme.html.twig', [
//             'articles' => $articles,
//         ]);
//     }




    //-----------------------------------------------PROMO--------------------------

    // /**
    //  * @Route("/promotion/promotion", name="app_article_promotion")
    //  * 
    //  */
    // public function promotion( ArticleRepository $repository ): Response
    // {

    //     $articles = $repository->ArticleEnPromotion(0);

    //     return $this->render('article/Promo/promotion.html.twig', [
    //         'articles' => $articles,
    //     ]);
    // }
 
  


     



//     //-----------------------------------------------Favoris--------------------------
    
//     /**
//      * @Route("/favoris/ajout/{id}", name="app_ajout_favoris")
//      */
//     public function ajoutFavoris(Article $article,ManagerRegistry $doctrine,)
//     {
//         if(!$article){
//             throw new NotFoundHttpException('Pas d\'article trouvée');
//         }
//         $entityManager = $doctrine->getManager();
//         $article->addFavori($this->getUser());
//         $entityManager->persist($article);
//         $entityManager->flush();
//         return $this->redirectToRoute('app_article_index');
//     }

//     /**
//      * @Route("/favoris/retrait/{id}", name="app_retrait_favoris")
//      */
//    public function retraitFavoris(Article $article,ManagerRegistry $doctrine,)
//     {
//         if(!$article){
//             throw new NotFoundHttpException('Pas d\'article trouvée');
//         }
//         $entityManager = $doctrine->getManager();
//         $article->removeFavori($this->getUser());
//         $entityManager->persist($article);
//         $entityManager->flush();
//         return $this->redirectToRoute('app_article_index');
//     }

//      /**
//      * @Route("/favoris/index", name="app_index_favoris")
//      */
//     public function Favoris(ArticleRepository $articleRepository): Response
//     {
    
//         //$favoris = $this->$user->getFavoris();
//         return $this->render('Favoris/index.html.twig', [
//             'favoris' => $articleRepository->findAll(),
//         ]);
//     }









// }
