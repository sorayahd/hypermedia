<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session,ArticleRepository $articleRepository): Response
    {
        $panier = $session->get('panier', []);
        $total = 0;
        $quantityTotal = 0;

        $panierWithData = [];

        foreach($panier as $id => $quantity) {
            $panierWithData[]= [
                'product'=>$articleRepository->find($id),
                'quantity'=> $quantity
               
            ];
           // dd($panierWithData);
          
            if ($articleRepository->find($id)->getPromotion()> 0 )
            {
              $total += $articleRepository->find($id)->getPromotion() * $quantity;
              $quantityTotal += $quantity;
            } 
            else {
  
              $total += $articleRepository->find($id)->getPrix() * $quantity;
              $quantityTotal += $quantity;
           
          }
            
        }
      
        return $this->render('cart/index.html.twig', [
            'items'=>$panierWithData,
            'total'=>$total,
            'quantity'=>$quantityTotal
        ]);
    }

      /**
     * @Route("/cart/remove/{id}", name="remove_cart")
     *
      */
    
     public function removeCart(Article $article,SessionInterface $session ){
        $panier = $session->get('panier',[]);
        $id = $article->getId();
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
       $session->set("panier",$panier);
       return $this->redirect("app_cart");
       
    }

    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function add(Article $article, SessionInterface $session)
    {
        // $session = $request->getSession();
        $panier = $session->get('panier', []);
        $id = $article->getId();
        // $panier[$id] = 1;
        //$session->set('panier', $panier);



        if (!empty($panier[$id])) {
           
            $panier[$id]++;
        } else {

            $panier[$id] = 1;
           
        }

        $session->set('panier', $panier);
        //dd($session->get('panier'));
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/delete/{id}', name: 'delete_cart')]
    public function deleteCarte(Article $article,SessionInterface $session ){
        $panier = $session->get('panier',[]);
        $id = $article->getId();
        if (!empty($panier[$id])) {
           
                unset($panier[$id]);
            }
        
       $session->set("panier",$panier);
       return $this->redirect("app_cart");
       
    }
   



    #[Route('/cart/valider/{id}', name: 'valider_cart')]
    public function ValiderPanier(Article $article,SessionInterface $session ){
        $panier = $session->get('panier',[]);
        $id = $article->getId();
        if (!empty($panier[$id])) {
           
                unset($panier[$id]);
            }
        
       $session->set("panier",$panier);
       return $this->redirect("app_cart");
       
    }
}