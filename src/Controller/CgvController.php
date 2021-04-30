<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CgvController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/cgv", name="cgv")
     * @return Responce
    */

    public function index (Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('pages/cgv.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
        ]);

    }

}
