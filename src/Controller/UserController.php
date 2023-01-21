<?php

namespace App\Controller;

use App\Form\EditProfileType;
use App\Form\ResetPassType;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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


    #[Route('/oubli-pass', name:'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if($user){
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // On crée les données du mail
                $context = compact('url', 'user');

                // Envoi du mail
                $mail->send(
                    'no-reply@e-commerce.fr',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            // $user est null
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);
        
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }





































//     /**
//  * @Route("/oubli-pass", name="app_forgotten_password")
//  */
// public function oubliPass(Request $request, UserRepository $users, SendMailService $mailer, TokenGeneratorInterface $tokenGenerator,
// ManagerRegistry $doctrine): Response
// {
//     // On initialise le formulaire
//     $form = $this->createForm(ResetPassType::class);

//     // On traite le formulaire
//     $form->handleRequest($request);

//     // Si le formulaire est valide
//     if ($form->isSubmitted() && $form->isValid()) {
//         // On récupère les données
//         $donnees = $form->getData();

//         // On cherche un utilisateur ayant cet e-mail
//         $user = $users->findOneByEmail($donnees['email']);

//         // Si l'utilisateur n'existe pas
//         if ($user === null) {
//             // On envoie une alerte disant que l'adresse e-mail est inconnue
//             $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            
//             // On retourne sur la page de connexion
//             return $this->redirectToRoute('app_login');
//         }

//         // On génère un token
//         $token = $tokenGenerator->generateToken();

//         // On essaie d'écrire le token en base de données
//         try{
//             $user->setResetToken($token);
//             $entityManager = $doctrine->getManager();
//             $entityManager->persist($user);
//             $entityManager->flush();
//         } catch (\Exception $e) {
//             $this->addFlash('warning', $e->getMessage());
//             return $this->redirectToRoute('app_login');
//         }

//         // On génère l'URL de réinitialisation de mot de passe
//         $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
//         $context = [
//             'name' => $this->getUser()->getName(),
           
//         ];

//          $mailer->send('wearit@gmail.com', $this->getUser()->getEmail(), 
//             'Confirmation de votre commande','test',$context);

//             ;

//         // On crée le message flash de confirmation
//         $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

//         // On redirige vers la page de login
//         return $this->redirectToRoute('app_login');
//     }

//     // On envoie le formulaire à la vue
//     return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
// }








//      /**
//  * @Route("/reset_pass/{token}", name="app_reset_password")
//  */
// public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $userPassword)
// {
//     // On cherche un utilisateur avec le token donné
//     $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token' => $token]);

//     // Si l'utilisateur n'existe pas
//     if ($user === null) {
//         // On affiche une erreur
//         $this->addFlash('danger', 'Token Inconnu');
//         return $this->redirectToRoute('app_login');
//     }

//     // Si le formulaire est envoyé en méthode post
//     if ($request->isMethod('POST')) {
//         // On supprime le token
//         $user->setResetToken(null);

//         // On chiffre le mot de passe
//         $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

//         // On stocke
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($user);
//         $entityManager->flush();

//         // On crée le message flash
//         $this->addFlash('message', 'Mot de passe mis à jour');

//         // On redirige vers la page de connexion
//         return $this->redirectToRoute('app_login');
//     }else {
//         // Si on n'a pas reçu les données, on affiche le formulaire
//         return $this->render('security/reset_password.html.twig', ['token' => $token]);
//     }

// }










}
