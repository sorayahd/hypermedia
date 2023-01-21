<?php

namespace App\Controller;



use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Entity\ArticleSearch;
use App\Entity\Comments;
use App\Entity\SearchByTaille;
use App\Entity\User;
use App\Form\Article1Type;
use App\Form\ArticleSearchType;
use App\Form\CommentsType;
use App\Form\SearchByTailleType;
use App\Repository\ArticleLikeRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategorieArticleRepository;
use App\Repository\CommentsRepository;
use App\Repository\TailleRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
    
    #[Route('/all', name: 'app_article_index', methods: ['GET', 'POST']) ]
    public function index(ArticleRepository $articleRepository,SessionInterface $session,Request $request): Response
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
        $motcle = $request->get('recherche');
        $article = $articleRepository->findArticle($motcle);
        //$article = $articleRepository->findAll();
        $ArticleRecherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $article = [];
       
            $minPrice = $ArticleRecherche->getminPrix();
            $maxPrice = $ArticleRecherche->getmaxPrix();

            $article = $articleRepository->findByPrice($minPrice, $maxPrice);
               
        }

        return $this->render('article/index.html.twig', [
            'articles' => $article,
            'form' =>$form->createView()
            // 'items'=>$panierWithData,
            // 'total'=>$total,
            // 'quantity'=>$quantityTotal
        ]);
    }
    
   
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




    #[Route('/{id}', name: 'app_article_show', methods: ['GET','POST'])]
    public function show(Article $article,ArticleRepository $articleRepository,Connection $connection
    ,ArticleLikeRepository $articleLikeRepositor,Request $request,CommentsRepository $commentsRepository): Response
    {
        $cmpt = $article->getNbVu();
        $cmpt=$cmpt+1;
        $article->setNbVu($cmpt);
        // dd($article);
        $articleRepository->save($article, true);
        $cat = $article->getCategorie()->getId();
        $sql = "SELECT * FROM Article WHERE article.categorie_id = $cat ORDER BY RAND() LIMIT 5";
        $data = $connection->fetchAllAssociative($sql);
        
    //partie commentaire

    $comment = new Comments();
     $commentaire = $commentsRepository->findAll();
    $forms = $this->createForm(CommentsType::class, $comment);
    $forms->handleRequest($request);

    if ($forms->isSubmitted() && $forms->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setArticle($article);
        $commentsRepository->save($comment, true);

        return $this->redirectToRoute('app_article_show', ['id'=>$article->getId()]);
    }

   

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articleSame' =>$data,
            'forms' => $forms->createView(),
            'comment' => $commentaire,
        ]);
    }


    
    // #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    // {
    //     $form = $this->createForm(Article1Type::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $articleRepository->save($article, true);

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('article/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    // public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
    //         $articleRepository->remove($article, true);
    //     }

    //     return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    // }

 //----------------------------ARTICLE PAR GENRE----------------------

    /**
     * @Route("/genre/homme", name="app_article_homme_show" ) 
     */

    public function ArticleHomme( ArticleRepository $articleRepository ,Request $request): Response
    {

        $article = $articleRepository->findBy(['sexe' => 1]);
        $ArticleRecherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $article = [];
       
            $minPrice = $ArticleRecherche->getminPrix();
            $maxPrice = $ArticleRecherche->getmaxPrix();

            $article = $articleRepository->findByPrice($minPrice, $maxPrice);
               
        }

        return $this->render('article/ArticleHomme.html.twig', [
            'articles' => $article,
            'form' =>$form->createView()
            // 'items'=>$panierWithData,
            // 'total'=>$total,
            // 'quantity'=>$quantityTotal
        ]);
       
    }

     /**
     * @Route("/genre/femme", name="app_article_femme_show" )
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

        return $this->render('article/chaussures/chaussureHomme.html.twig', [
            'articles' => $articles,
        ]);
    }
 
      /**
     * @Route("/chaussure/femme", name="app_article_chaussure_femme")
     * 
     */
    public function chaussureFemme( ArticleRepository $repository ): Response
    {

        $articles =$repository->findBy([
            'sexe' => 2,
            'categorie'=> 3
    ]);

        return $this->render('article/chaussures/chaussureFemme.html.twig', [
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
    public function ajoutFavoris(Article $article,ManagerRegistry $doctrine)
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

     /**
     * @Route("/recherche/index", name="app_recherche")
     */
    public function rechercheAvancé(Request $request,ArticleRepository $articleRepository): Response
    {
        
        $ArticleRecherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
        $form->handleRequest($request);
        $article = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $minPrice = $ArticleRecherche->getminPrix();
            $maxPrice = $ArticleRecherche->getmaxPrix();

            $article = $articleRepository->findByPrice($minPrice, $maxPrice);
               
        }
        return  $this->render('article/index.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route("/like/{id}", name="app_like")
     */
    public function ajoutLike(Article $article,ManagerRegistry $doctrine,ArticleLikeRepository $articleLikeRepository)
    {
        $entityManager = $doctrine->getManager();
       
        $cmpt = $article->getnombreLike();

        if ($article->isLikedByUser($this->getUser())){
            $like = $articleLikeRepository->findOneBy([
                'article' => $article,
                'user' => $this->getUser()
             
            ]);

        $cmpt = $cmpt-1;
        $article->setnombreLike($cmpt);
       // $entityManager->persist($cmpt);
        $entityManager->remove($like);
        $entityManager->flush();
        return $this->redirectToRoute('app_article_show',['id' => $article->getId()]);
        }

        
        $like = new ArticleLike();
       
        $cmpt = $cmpt + 1;
        $like->setArticle($article);
        $like->setUser($this->getUser()) .
        $article->setnombreLike($cmpt);
        //$entityManager->persist($article);
        $entityManager->persist($like);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_article_show',['id' => $article->getId()]);
    }

   

   
     /**
     * @Route("/ArticleRecommande/plusAime", name="plusAime" )
     * 
     */

     public function PlusAime(ArticleRepository $articleRepository,ArticleLikeRepository $articleLikeRepository,Request $request ): Response
     {
        $article = $articleRepository->findLike();
       // dd($article);


        $ArticleRecherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $article = [];
       
            $minPrice = $ArticleRecherche->getminPrix()/100;
            $maxPrice = $ArticleRecherche->getmaxPrix()/100;

            $article = $articleRepository->findByPrice($minPrice, $maxPrice);
               
        }

        return $this->render('article/plusAime.html.twig', [
            'articles' => $article,
            'form' =>$form->createView()
           
        ]);
         
     }

     /**
     * @Route("/ArticleRecommande/plusConsulte", name="plusConsulte" )
     * 
     */

     public function PlusConsulte(ArticleRepository $articleRepository,ArticleLikeRepository $articleLikeRepository,Request $request ): Response
     {
       
        $article = $articleRepository->findPlusVu();
        // dd($article);
 
 
         $ArticleRecherche = new ArticleSearch();
         $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
             $article = [];
        
             $minPrice = $ArticleRecherche->getminPrix()/100;
             $maxPrice = $ArticleRecherche->getmaxPrix()/100;
 
             $article = $articleRepository->findByPrice($minPrice, $maxPrice);
                
         }
 
         return $this->render('article/plusVu.html.twig', [
             'articles' => $article,
             'form' =>$form->createView()
            
         ]);
      
     
    

         
     }


     /**
     * @Route("/ArticleRecommande/plusVendu", name="plusVendu" )
     * 
     */

     public function PlusVnedu(ArticleRepository $articleRepository,Connection $connection
     ,ArticleLikeRepository $articleLikeRepository ,Request $request): Response
     {
         
         $sql = "SELECT id,categorie_id,sexe_id,nom,description,prix,image,promotion, articles_id,sum(quantity)
          AS nbAcht FROM cart_details inner join
         article on article.id=cart_details.articles_id GROUP BY articles_id order by nbAcht DESC";
      
         $data = $connection->fetchAllAssociative($sql);
         $ArticleRecherche = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $ArticleRecherche);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = [];
       
            $minPrice = $ArticleRecherche->getminPrix()*100;
            $maxPrice = $ArticleRecherche->getmaxPrix()*100;

            $data = $articleRepository->findByPrice($minPrice, $maxPrice);
               
        }

        return $this->render('article/plusAchete.html.twig', [
            'articles' => $data,
            'form' =>$form->createView()
           
        ]);
     }




 /**
     * @Route("/ArticleRecommande/recommande", name="app_article_recommande" ) 
     */

     public function recommande( ArticleRepository $articleRepository ,Request $request,TailleRepository $tailleRepository,     
     Connection $connection,ManagerRegistry $doctrine): Response
     { 
        $entityManager=$doctrine->getManager();
 
        //$article = $articleRepository->findAll();
         $ArticleRecherche = new SearchByTaille();

         $form = $this->createForm(SearchByTailleType::class, $ArticleRecherche);
         $form->handleRequest($request);
         //$taillee =$form->get('minTaille')->getData();
         $article = [];
         if ($form->isSubmitted() && $form->isValid()) {
             $taille=$ArticleRecherche->getMinTaille();
            if ($taille = "")
                $article = $entityManager->getRepository(Taille::class)->findBy(['taille_min' => $taille]);
            dd($article);

        
            

           
         }
 

      
       

        //$cat = $article->getCategorie()->getId();
        //$sql = "SELECT * FROM Taille WHERE $taillee BETWEEN taille.taille_min AND taille.taille_max ";
        //$data = $connection->fetchAllAssociative($sql);
        //dd($data);
        
    //partie commentaire

  


         return $this->render('article/recommande.html.twig', [
             'articles' => $article,
             'form' =>$form->createView()
             // 'items'=>$panierWithData,
             // 'total'=>$total,
             // 'quantity'=>$quantityTotal
         ]);
        
     }
 

     


}
