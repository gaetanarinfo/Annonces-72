<?php

namespace App\Controller;

use App\Entity\Annonces;
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

class HomeController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     * @return Responce
    */

    public function index (Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonces = $repositoryAnnonces->findLatestNonPremium();
        $annoncesPremium = $repositoryAnnonces->findLatestPremium();
        $annoncesCount = $repositoryAnnonces->findCountAll();
        $annoncesCountPremium = $repositoryAnnonces->findCountAllPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $annoncesVerif = $repositoryAnnonces->findAll();
        $dateFinish = new DateTime('now');

        foreach ($annoncesVerif as $value) {

            if( $value->getTerminedAtPremium() != null)
            {
                dump($value->getTerminedAtPremium());

                if($dateFinish > $value->getTerminedAtPremium())
                {
                    $value->setPremium(0);
                    $value->setCreatedAtPremium(null);
                    $value->setTerminedAtPremium(null);
                    $this->em->persist($repositoryAnnonces->find($value->getId()));
                    $this->em->flush();
                }
            }

        }

        return $this->render('pages/home.html.twig', [
            'formContact' => $formContact->createView(),
            'annonces' => $annonces,
            'annoncesPremium' => $annoncesPremium,
            'annonceLatest' => $annoncesPremium,
            'annoncesCount' => $annoncesCount,
            'annoncesCountPremium' => $annoncesCountPremium
        ]);

    }

}
