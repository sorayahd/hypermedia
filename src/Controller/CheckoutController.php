<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\CartDetail;
use App\Entity\CartDetails;
use App\Entity\StatusCommande;
use App\Entity\User;
use App\Form\CheckoutType;
use App\Repository\ArticleRepository;
use App\Repository\CartDetailsRepository;
use App\Repository\CartRepository;
use App\Repository\StatusCommandeRepository;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class CheckoutController extends AbstractController
{

    /**
     * @Route("/recap", name="recap")
     */
    public function recap(CartRepository $cartRepository): Response
    {
        return $this->render('checkout/recap.html.twig');
    }

    /**
     * @Route("/checkout", name="checkout")
     */

    public function new (
        Request $request,SessionInterface $session,
        ArticleRepository $articleRepository, ManagerRegistry $doctrine,
        StatusCommandeRepository $statusRepo,CartDetailsRepository $cartDetailsRepository): Response
    {

        $panier = $session->get('panier', []);
        $total = 0;
        $quantityTotal = 0;

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $articleRepository->find($id),
                'quantity' => $quantity

            ];

            if ($articleRepository->find($id)->getPromotion() > 0) {
                $total += $articleRepository->find($id)->getPromotion() * $quantity;
                $quantityTotal += $quantity;
            } else {

                $total += $articleRepository->find($id)->getPrix() * $quantity;
                $quantityTotal += $quantity;

            }


        }
        
        //----------        -----------------------------------
        $cart = new Cart();

        $form = $this->createForm(CheckoutType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
           
            $status= $statusRepo->find('1');
            $reference = $this->generateUuid();
            $data = $form->getData();
            $address = $data['adresse'];
            $transporteur = $data['transporteur'];
            $prix = $transporteur->getPrix();
            $cart->setReference($reference);
            $cart->setStatus($status);
            $cart->setUser($this->getUser());
            $cart->setAdresse($address);
            $cart->setTotal($total);
            $cart->setNbArticle($quantityTotal);
            $cart->setTransporteur($transporteur);
            $em->persist($cart);
            
            for ($i = 0; $i < count($panierWithData ); $i++) {
            $commande = new CartDetails();
           $commande->setCart($cart);
           $commande->setArticles($panierWithData [$i]['product']);
           $commande->setQuantity($panierWithData [$i]['quantity']);
           $commande->setPrice($panierWithData[$i]['product']->getPrix() *$panierWithData [$i]['quantity'] );
        
           $em->persist($commande);
          
           }

            $em->flush();
            $session->clear();
         
          
            return $this->redirectToRoute('recap');
         

           
        }
      
        return $this->renderForm('checkout/index.html.twig', [

            'cart' => $cart,
            'checkout' => $form,
            'items' => $panierWithData,
            'total' => $total,
            'quantity' => $quantityTotal
        ]);
    }

    public function generateUuid()
    {
        // Initialise le g??n??rateur de nombres al??atoires Mersenne Twister
        mt_srand((double)microtime()*100);

        //strtoupper : Renvoie une cha??ne en majuscules
        //uniqid : G??n??re un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //G??n??rer une cha??ne d'un octet ?? partir d'un nombre
        $hyphen = chr(10);

        //substr : Retourne un segment de cha??ne
        $uuid = ""
            . substr($charid, 0, 8) . $hyphen;
            
            
        // .substr($charid, 12, 4).$hyphen
        // .substr($charid, 16, 4).$hyphen
        // 
        
        return $uuid;
    }

    /**
     * @Route("/OrderDetails/{id}", name="OrderDetails")
     */
    // public function indexAction(ManagerRegistry $doctrine,CartRepository $cartRepository,$id) {

    //     $em = $doctrine->getManager();
    //     return $this->render('panier/show.html.twig', [
    //         'carts' => $cartRepository->findOneBy(),
    //     ]);
    // }
    #[Route('/OrderDetails/{id}', name: 'OrderDetails_show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('panier/show.html.twig', [
            'carts' => $cart,
        ]);
    }
    
}


















        
   

