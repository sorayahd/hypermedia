<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Parser\ParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session,ArticleRepository $articleRepository): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach($panier as $id => $quantity) {
            $panierWithData[]= [
                'product'=>$articleRepository->find($id),
                'quantity'=> $quantity
               
            ];
        
            
        }
       // dd($panierWithData);
        return $this->render('cart/index.html.twig', [
            'items'=>$panierWithData
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function add($id, SessionInterface $session)
    {
        // $session = $request->getSession();
        $panier = $session->get('panier', []);
        // $panier[$id] = 1;
        //$session->set('panier', $panier);



        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {

            $panier[$id] = 1;
           
        }

        $session->set('panier', $panier);
        //dd($session->get('panier'));
        return $this->redirectToRoute("app_article_index");
    }


    #[Route('/cart/remove/{id}', name: 'remove_cart')]
     public function removeCart($id,SessionInterface $session ){
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirect("app_cart");
    }


}
