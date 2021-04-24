<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Responce
    */

    public function index (Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonces = $repositoryAnnonces->findLatest();
        $annoncesCount = $repositoryAnnonces->findCountAll();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('pages/home.html.twig', [
            'formContact' => $formContact->createView(),
            'annonces' => $annonces,
            'annonceLatest' => $annonces,
            'annoncesCount' => $annoncesCount
        ]);

    }

}
