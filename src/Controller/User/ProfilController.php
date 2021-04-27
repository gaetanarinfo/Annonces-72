<?php

namespace App\Controller\User;

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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ProfilController extends AbstractController
{

    /**
     *
     * @var UserRepository
     * @var EntityManagerInterface
     */
    private $repository;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/profil", name="profil", methods="GET|POST")
    */
    public function index(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, LikeAnnoncesRepository $repositoryLike, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $userLike = $repositoryLike->findBy(array('userId' => $this->getUser()->getId()));

        if($userLike != null)
        {   
            $annoncesLike = $repositoryAnnonces->findBy(array('id' => $userLike));
        }else{
            $annoncesLike = 0;
        }

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/index.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'annoncesLike' => $annoncesLike,
            'countMail' => $countMail
        ]);
    }

    /**
     * @Route("/profil/edit", name="user.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, UserPasswordEncoderInterface $passwordEncoder, MailboxRepository $repositoryMailbox)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType3::class, $user);
        $form->handleRequest($request);

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonces = $repositoryAnnonces->findLatest();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if($form->isSubmitted() && $form->isValid()) {
                $user->setUpdatedAt(new \DateTime('now'));
                $this->em->flush();
                $this->addFlash('success', 'Profil modifié avec succès.');
                return $this->redirectToRoute('user.edit');      
        }

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annonces,
            'countMail' => $countMail
        ]);
    }

    /**
     * @Route("/profil/delete", name="user.delete", methods="GET|POST")
     */
    public function delete(Request $request)
    {
        $user = $this->getUser();
        $session = new Session();
        $session->invalidate();
        $this->em->remove($user);
        $this->em->flush();
        $this->addFlash('success', 'Votre compte à été supprimé');
        return $this->redirectToRoute('home');
    }

    // /**
    // * @Route("/profil", name="user.avatar.delete")
    // */
    // public function deleteImage(Avatar $picture, Request $request)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $em->remove($picture);
    //     $em->flush();
    //     $this->addFlash('success', 'Image de profil supprimé avec succès');
    //     return $this->redirectToRoute('user.index');
    // }

    // /**
    //  * @Route("/profil/{username}", name="user.public", methods="GET|POST")
    //  */
    // public function show(User $user, PropertyRepository $propertyRepository, RentRepository $rentRepository, AppartementARepository $appartementARepository, AppartementBRepository $appartementBRepository, BlogRepository $blogRepository)
    // {
    //     $username = $user->getUsername();
    //     $properties = $propertyRepository->findAllProperty($username);
    //     $username_role = $user->getRoles();
    //     $rents = $rentRepository->findAllRent($username);
    //     $appartementAs = $appartementARepository->findAllAppartementA($username);
    //     $appartementBs = $appartementBRepository->findAllAppartementB($username);
    //     $blogs = $blogRepository->findAllBlog($username);

    //     if($username == null || $username != $user->getUsername()){

    //         return $this->redirectToRoute('home');

    //     }else{

    //         if($username_role[0] == 'ROLE_ADMIN' || $username_role[0] == 'ROLE_SUPER_ADMIN') {

    //             return $this->redirectToRoute('home');

    //         }else{

    //             return $this->render('user/public.html.twig', [
    //                 'user' => $user,
    //                 'properties' => $properties,
    //                 'rents' => $rents,
    //                 'appartementAs' => $appartementAs,
    //                 'appartementBs' => $appartementBs,
    //                 'blogs' => $blogs
    //             ]);

    //         }
    //     }
    // }

    /**
     * @Route("/profil/annonces", name="user.annonces", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function annonces(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();
        $annoncesUser = $repositoryAnnonces->findBy(array('author' => $this->getUser()->getUsername()));

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/annonces.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'annoncesUser' => $annoncesUser,
            'countMail' => $countMail
        ]);
    }

    /**
     * @Route("/profil/credit", name="user.credit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function credit(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CreditsRepository $creditsRepository, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('profil');

        }

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();
        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/credit.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'countMail' => $countMail,
            'transaction' => $creditsRepository->paginateAllVisible($this->getUser()->getId(), $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * @Route("/profil/credit/create/10/{token}", name="user.credit.create.10", methods="GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param DeleteMemberResponder     $responder
     * @param Request                   $request
     * 
     * @return Response
     *
     * @throws InvalidCsrfTokenException
     */
    public function creditCreate10(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.credit');

        }

        $token = new CsrfToken('token_paypal_10', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            $credit = new Credits;
            $credit->setAmount(10);
            $credit->setDescription('Acheter pour 10 euros de crédits');
            $credit->setStatus('validé');
            $credit->setUserId($this->getUser()->getId());
            $credit->setIdTrans(uniqid());
            $credits = $this->getUser()->getCredits();
            $this->getUser()->setCredits($credits + 10);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($credit);
            $entityManager->flush();
            $this->addFlash('success', 'Achat éffectué avec succès');

        }

        return $this->redirectToRoute('profil');
    }

     /**
     * @Route("/profil/credit/create/25/{token}", name="user.credit.create.25", methods="GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param DeleteMemberResponder     $responder
     * @param Request                   $request
     * 
     * @return Response
     *
     * @throws InvalidCsrfTokenException
     */
    public function creditCreate25(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.credit');

        }

        $token = new CsrfToken('token_paypal_25', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            $credit = new Credits;
            $credit->setAmount(25);
            $credit->setDescription('Acheter pour 25 euros de crédits');
            $credit->setStatus('validé');
            $credit->setUserId($this->getUser()->getId());
            $credit->setIdTrans(uniqid());
            $credits = $this->getUser()->getCredits();
            $this->getUser()->setCredits($credits + 10);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($credit);
            $entityManager->flush();
            $this->addFlash('success', 'Achat éffectué avec succès');

        }

        return $this->redirectToRoute('user.credit');
    }

    /**
     * @Route("/profil/credit/create/50/{token}", name="user.credit.create.50", methods="GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param DeleteMemberResponder     $responder
     * @param Request                   $request
     * 
     * @return Response
     *
     * @throws InvalidCsrfTokenException
     */
    public function creditCreate50(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.credit');

        }

        $token = new CsrfToken('token_paypal_50', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            $credit = new Credits;
            $credit->setAmount(50);
            $credit->setDescription('Acheter pour 50 euros de crédits');
            $credit->setStatus('validé');
            $credit->setUserId($this->getUser()->getId());
            $credit->setIdTrans(uniqid());
            $credits = $this->getUser()->getCredits();
            $this->getUser()->setCredits($credits + 50);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($credit);
            $entityManager->flush();
            $this->addFlash('success', 'Achat éffectué avec succès');

        }

        return $this->redirectToRoute('user.credit');
    }

     /**
     * @Route("/profil/credit/create/150/{token}", name="user.credit.create.150", methods="GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param DeleteMemberResponder     $responder
     * @param Request                   $request
     * 
     * @return Response
     *
     * @throws InvalidCsrfTokenException
     */
    public function creditCreate150(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, CsrfTokenManagerInterface $csrfTokenManager)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.credit');

        }

        $token = new CsrfToken('token_paypal_150', $request->attributes->get('token'));
 
        // Action is stopped since token is not allowed!
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('CSRF Token est non valide');
        }else{

            $credit = new Credits;
            $credit->setAmount(150);
            $credit->setDescription('Acheter pour 150 euros de crédits');
            $credit->setStatus('validé');
            $credit->setUserId($this->getUser()->getId());
            $credit->setIdTrans(uniqid());
            $credits = $this->getUser()->getCredits();
            $this->getUser()->setCredits($credits + 150);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($credit);
            $entityManager->flush();
            $this->addFlash('success', 'Achat éffectué avec succès');

        }

        return $this->redirectToRoute('user.credit');
    }

    /**
     * @Route("/profil/favoris", name="user.favoris", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favoris(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, LikeAnnoncesRepository $repositoryLike, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.favoris');

        }

        $userLike = $repositoryLike->findBy(array('userId' => $this->getUser()->getId()));

        if($userLike != null)
        {   
            $annoncesLike = $repositoryAnnonces->findBy(array('id' => $userLike));
        }else{
            $annoncesLike = 0;
        }

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/favoris.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'annoncesLike' => $annoncesLike,
            'countMail' => $countMail
        ]);
    }

    /**
    * @Route("/profil/favoris/{id}", name="user.favoris.delete", methods="GET|POST")
    */
    public function deleteFavoris(LikeAnnonces $annonces, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonces);
        $em->flush();
        $this->addFlash('success', 'Annonce supprimé des favoris');
        return $this->redirectToRoute('user.favoris');
    }

    /**
    * @Route("/profil/annonces/delete/{id}", name="user.annonce.delete")
    */
    public function deleteImage(Annonces $annonce, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        $this->addFlash('success', 'Annonce supprimé avec succès');
        return $this->redirectToRoute('user.annonces');
    }

    /**
     * @Route("/profil/boite-de-reception", name="user.mailbox", methods="GET|POST")
    */
    public function mailbox(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, LikeAnnoncesRepository $repositoryLike, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $userLike = $repositoryLike->findBy(array('userId' => $this->getUser()->getId()));

        if($userLike != null)
        {   
            $annoncesLike = $repositoryAnnonces->findBy(array('id' => $userLike));
        }else{
            $annoncesLike = 0;
        }

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/mailbox.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'annoncesLike' => $annoncesLike,
            'mailbox' => $repositoryMailbox->paginateAllVisible($this->getUser()->getUsername(), $request->query->getInt('page', 1)),
            'countMail' => $countMail
        ]);
    }

    /**
     * @Route("/profil/boite-de-reception/read/{id}", name="user.mailbox.read", methods="GET|POST")
    */
    public function mailboxRead(Mailbox $mailbox, Request $request)
    {

        if($mailbox->getRecipient() == $this->getUser()->getUsername())
        {

            $mailbox->setIsRead(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mailbox);
            $entityManager->flush();
            $this->addFlash('success', 'Message archivé avec succès');
        }

        return $this->redirectToRoute('user.mailbox');
    }

    /**
     * @Route("/profil/boite-de-reception/delete/{id}", name="user.mailbox.delete", methods="GET|POST")
    */
    public function mailboxDelete(Mailbox $mailbox, Request $request)
    {

        if($mailbox->getRecipient() == $this->getUser()->getUsername())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mailbox);
            $entityManager->flush();
            $this->addFlash('success', 'Message supprimé avec succès');
        }

        return $this->redirectToRoute('user.mailbox');
    }

    /**
     * @Route("/profil/boite-de-reception/message/{id}", name="user.mailbox.view", methods="GET|POST")
    */
    public function mailboxView(Mailbox $mailbox, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, LikeAnnoncesRepository $repositoryLike, MailboxRepository $repositoryMailbox)
    {

        // if($mailbox->getRecipient() == $this->getUser()->getUsername())
        // {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->remove($mailbox);
        //     $entityManager->flush();
        //     $this->addFlash('success', 'Message supprimé avec succès');
        // }

        $mailboX = new Mailbox();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $formMailbox = $this->createForm(MailboxType::class, $mailboX);
        $formMailbox->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.mailbox');

        }

        if ($formMailbox->isSubmitted() && $formMailbox->isValid()) {
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mailboX);
            $entityManager->flush();
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('user.mailbox');

        }

        $userLike = $repositoryLike->findBy(array('userId' => $this->getUser()->getId()));

        if($userLike != null)
        {   
            $annoncesLike = $repositoryAnnonces->findBy(array('id' => $userLike));
        }else{
            $annoncesLike = 0;
        }

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        return $this->render('user/mailboxView.html.twig', [
            'formContact' => $formContact->createView(),
            'formMailbox' => $formMailbox->createView(),
            'annonceLatest' => $annoncesPremium,
            'annoncesLike' => $annoncesLike,
            'mailbox' => $mailbox,
            'countMail' => $countMail
        ]);
    }

}

?>
