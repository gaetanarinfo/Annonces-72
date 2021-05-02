<?php

namespace App\Controller\Moderateur;

use App\Entity\Annonces;
use App\Entity\Avatar;
use App\Entity\Contact;
use App\Entity\Credits;
use App\Entity\LikeAnnonces;
use App\Entity\Mailbox;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\MailboxType;
use App\Form\UserType3;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use App\Repository\CreditsRepository;
use App\Repository\LikeAnnoncesRepository;
use App\Repository\MailboxRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ModoAnnoncesController extends AbstractController
{
    /**
         * @Route("/moderateur/annonces/", name="modo.annonces.show", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function show(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {

            $contact = new Contact();
            $formContact = $this->createForm(ContactType::class, $contact);
            $formContact->handleRequest($request);

            $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
            if ($formContact->isSubmitted() && $formContact->isValid()) {
            
                $notif->notify($contact, $annoncesMail);
                $this->addFlash('success', 'Votre message à bien été transmis');
                return $this->redirectToRoute('profil');

            }

            $annoncesPremium = $repositoryAnnonces->findLatestPremium();
            $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

            $annoncesValid = $repositoryAnnonces->findCountAnnoncesModerateur();
            $annoncesValid2 = $repositoryAnnonces->findCountAnnoncesModerateur2();

            return $this->render('moderateur/showAnnonces.html.twig', [
                'formContact' => $formContact->createView(),
                'annonceLatest' => $annoncesPremium,
                'countMail' => $countMail,
                'annonces' => $repositoryAnnonces->paginateAllVisibleModerateur($request->query->getInt('page', 1)),
                'annonces2' => $repositoryAnnonces->paginateAllVisibleModerateur2($request->query->getInt('page', 1)),
                'annoncesValid' => $annoncesValid,
                'annoncesValid2' => $annoncesValid2
            ]);
        }

    /**
         * @Route("/moderateur/annonces/valide/{id}", name="modo.annonces.valide", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function edit(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {
            if($annonces->getIsValid() != 1)
            {
                $annonces->setIsValid(1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($annonces);
                $entityManager->flush();
                $this->addFlash('success', 'L\'annonce à été validé');
                return $this->redirectToRoute('modo.annonces.show');

            }else{
                $this->addFlash('error', 'L\'annonce est déjà validé');
                return $this->redirectToRoute('modo.annonces.show');
            }   

        }

    /**
         * @Route("/moderateur/annonces/delete/{id}", name="modo.annonces.delete", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function delete(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonces);
            $entityManager->flush();
            $this->addFlash('success', 'L\'annonce à été supprimer');
            return $this->redirectToRoute('modo.annonces.show');
        }    
}