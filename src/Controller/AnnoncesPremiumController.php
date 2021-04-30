<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\AnnoncesSearch;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Credits;
use App\Entity\LikeAnnonces;
use App\Entity\User;
use App\Entity\VoteAnnonces;
use App\Form\AnnoncesSearchType;
use App\Form\AnnoncesType;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeAnnoncesRepository;
use App\Repository\UserRepository;
use App\Repository\VoteAnnoncesRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AnnoncesPremiumController extends AbstractController
{

    private $repositoryAnnonces;

    /**
     * @var AnnoncesRepository
     * @var EntityManagerInterface
     */

    public function __construct(AnnoncesRepository $repositoryAnnonces, EntityManagerInterface $em)
    {
        $this->repositoryAnnonces = $repositoryAnnonces;
        $this->em = $em;
    }

    /**
     * @Route("/premium/{id}", name="annonces.premium")
     * @return Responce
    */
    public function premium(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, LikeAnnoncesRepository $repositoryLike)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonceLatest = $repositoryAnnonces->findLatest();

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $annoncesVerif = $repositoryAnnonces->findBy(array('id' => $request->get('id')));
        $username = $this->getUser()->getUsername();

        foreach ($annoncesVerif as $value) {

            if($value->getAuthor() == $username)
            {

                if($annonces->getPremium() == 1)
                {
        
                    return $this->redirectToRoute('user.annonces');
        
                }else{
        
                    return $this->render('pages/premium.html.twig', [
                        'formContact' => $formContact->createView(),
                        'annonceLatest' => $annonceLatest,
                        'annoncesId' => $annonces
                    ]);
        
                }

            }else{
                return $this->redirectToRoute('home');
            }

        }
        
    }

    /**
     * @Route("/premium/achat/10/{id}:{token}", name="annonces.premium.achat10")
     * @return Responce
     * @param CsrfTokenManagerInterface $csrfTokenManager
    */
    public function premium1(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonceLatest = $repositoryAnnonces->findLatest();

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $token = new CsrfToken('token_achat_10', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            if($this->getUser()->getCredits() > 9)
            {

                $date = new \DateTime('now');
                $date->add(new DateInterval('P7D'));

                $credit = new Credits;
                $annonces->setPremium(1);
                $annonces->SetCreatedAtPremium(new DateTime('now'));
                $annonces->SetTerminedAtPremium($date);
                $credit->setAmount(10);
                $credit->setDescription('Annonce premium 1 semaines');
                $credit->setStatus('validé');
                $credit->setUserId($this->getUser()->getId());
                $credit->setIdTrans(uniqid());
                $credits = $this->getUser()->getCredits();
                $this->getUser()->setCredits($credits - 10);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($credit);
                $entityManager->flush();
                $this->addFlash('success', 'Achat éffectué avec succès');

            }else{
                $this->addFlash('error', 'Vous n\'avez pas assez de crédits');
                return $this->redirectToRoute('user.credit');
            }

        }

        return $this->redirectToRoute('user.annonces');
        
    }

    /**
     * @Route("/premium/achat/25/{id}:{token}", name="annonces.premium.achat25")
     * @return Responce
     * @param CsrfTokenManagerInterface $csrfTokenManager
    */
    public function premium2(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonceLatest = $repositoryAnnonces->findLatest();

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $token = new CsrfToken('token_achat_25', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            if($this->getUser()->getCredits() > 24)
            {

                $date = new \DateTime('now');
                $date->add(new DateInterval('P15D'));

                $credit = new Credits;
                $annonces->setPremium(1);
                $annonces->SetCreatedAtPremium(new DateTime('now'));
                $annonces->SetTerminedAtPremium($date);
                $credit->setAmount(25);
                $credit->setDescription('Annonce premium 2 semaines');
                $credit->setStatus('validé');
                $credit->setUserId($this->getUser()->getId());
                $credit->setIdTrans(uniqid());
                $credits = $this->getUser()->getCredits();
                $this->getUser()->setCredits($credits - 25);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($credit);
                $entityManager->flush();
                $this->addFlash('success', 'Achat éffectué avec succès');

            }else{
                $this->addFlash('error', 'Vous n\'avez pas assez de crédits');
                return $this->redirectToRoute('user.credit');
            }  

        }

        return $this->redirectToRoute('user.annonces');
        
    }

    /**
     * @Route("/premium/achat/50/{id}:{token}", name="annonces.premium.achat50")
     * @return Responce
     * @param CsrfTokenManagerInterface $csrfTokenManager
    */
    public function premium3(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonceLatest = $repositoryAnnonces->findLatest();

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $token = new CsrfToken('token_achat_50', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            if($this->getUser()->getCredits() > 49)
            {
                $date = new \DateTime('now');
                $date->add(new DateInterval('P1M'));

                $credit = new Credits;
                $annonces->setPremium(1);
                $annonces->SetCreatedAtPremium(new DateTime('now'));
                $annonces->SetTerminedAtPremium($date);
                $credit->setAmount(50);
                $credit->setDescription('Annonce premium 1 mois');
                $credit->setStatus('validé');
                $credit->setUserId($this->getUser()->getId());
                $credit->setIdTrans(uniqid());
                $credits = $this->getUser()->getCredits();
                $this->getUser()->setCredits($credits - 50);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($credit);
                $entityManager->flush();
                $this->addFlash('success', 'Achat éffectué avec succès');
            }else{
                $this->addFlash('error', 'Vous n\'avez pas assez de crédits');
                return $this->redirectToRoute('user.credit');
            }      

        }

        return $this->redirectToRoute('user.annonces');
        
    }

}