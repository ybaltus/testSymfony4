<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        $new_contact = new Contact();

        $form = $this->createForm(ContactType::class, $new_contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
//            $task = $form->getData();
            $this->addFlash(
                'success',
                'Le contact a bien été ajouté.'
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($new_contact);
            $entityManager->flush();
            $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
