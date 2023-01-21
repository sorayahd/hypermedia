<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

  

    /**
     * @var UserRepository
     */
    private $userRepository;
    private $doctrine;
    public function __construct(UserRepository $userRepository,ManagerRegistry $doctrine)
    {
       
        $this->userRepository = $userRepository;
        $this->doctrine = $doctrine;
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    ManagerRegistry $doctrine,SendMailService $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
           
            $user->setToken($this->generateToken());
           
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $mailer->sendEmail($user->getEmail(), $user->getToken());
            $this->addFlash("success", "compte crée avec succes ,consultez vos mails pour le valider !");
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token,)
    { 
        $user = $this->userRepository->findOneBy(["token" => $token]);
        if($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "votre compte à bien été activé,vous pouvez desormais vous connecter  !");
            return $this->redirectToRoute("app_login");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('app_register ');
        }
        // return $this->json($token);
    }


     /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
