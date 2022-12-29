<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/users/profil/modifier", name="users_profil_modifier")
     */
    public function editProfile(Request $request,ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/users/pass/modifier", name="users_pass_modifier")
     */
    // public function editPass(Request $request, UserPasswordEnx $passwordEncoder)
    // {
    //     if($request->isMethod('POST')){
    //         $em = $this->getDoctrine()->getManager();

    //         $user = $this->getUser();

    //         // On vérifie si les 2 mots de passe sont identiques
    //         if($request->request->get('pass') == $request->request->get('pass2')){
    //             $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
    //             $em->flush();
    //             $this->addFlash('message', 'Mot de passe mis à jour avec succès');

    //             return $this->redirectToRoute('users');
    //         }else{
    //             $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
    //         }
    //     }

    //     return $this->render('users/editpass.html.twig');
    // }











}
