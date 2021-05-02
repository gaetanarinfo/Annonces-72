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

class ModoUsersController extends AbstractController
{
    /**
         * @Route("/moderateur/users/", name="modo.users.show", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function show(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, UserRepository $repositoryUsers, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
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

            $usersValid = $repositoryUsers->findCountUsersModerateur();
            $usersValid2 = $repositoryUsers->findCountUsersModerateur2();

            return $this->render('moderateur/showUsers.html.twig', [
                'formContact' => $formContact->createView(),
                'annonceLatest' => $annoncesPremium,
                'countMail' => $countMail,
                'users' => $repositoryUsers->paginateAllVisibleModerateur($request->query->getInt('page', 1)),
                'users2' => $repositoryUsers->paginateAllVisibleModerateur2($request->query->getInt('page', 1)),
                'usersValid' => $usersValid,
                'usersValid2' => $usersValid2
            ]);
        }

    /**
         * @Route("/moderateur/users/banned/{id}", name="modo.users.banned", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function banned(User $users, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {
            if($users->getIsActive() == 1)
            {
                $users->setIsActive(0);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($users);
                $entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur a été banni');
                return $this->redirectToRoute('modo.users.show');

            }else{
                $this->addFlash('error', 'L\'utilisateur a déjà été banni');
                return $this->redirectToRoute('modo.users.show');
            }   

        }

        /**
         * @Route("/moderateur/users/valide/{id}", name="modo.users.valide", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function valide(User $users, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {
            if($users->getIsActive() != 1)
            {
                $users->setIsActive(1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($users);
                $entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur a été validé');
                return $this->redirectToRoute('modo.users.show');

            }else{
                $this->addFlash('error', 'L\'utilisateur a déjà été validé');
                return $this->redirectToRoute('modo.users.show');
            }   

        }

    /**
         * @Route("/moderateur/users/delete/{id}", name="modo.users.delete", methods="GET|POST")
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function delete(User $users, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($users);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur à été supprimer');
            return $this->redirectToRoute('modo.users.show');
        }    
}