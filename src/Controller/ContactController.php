<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $manager ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $contact->setUser($this->getUser());
            $contact = $form->getData();
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash('succes', 'Votre demande a bien été prise en compte !');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact/message', name: 'app_contact_message')]
    public function message(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/message.html.twig', [
            'message ' => $contactRepository->findAll(),
        ]);
    }




}
