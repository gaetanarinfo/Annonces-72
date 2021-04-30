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
use App\Entity\VoteUser;
use App\Form\AnnoncesEditType;
use App\Form\AnnoncesSearchType;
use App\Form\AnnoncesType;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeAnnoncesRepository;
use App\Repository\MailboxRepository;
use App\Repository\UserRepository;
use App\Repository\VoteAnnoncesRepository;
use App\Repository\VoteUserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AnnoncesController extends AbstractController
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
     * @Route("/annonces", name="annonces")
     * @return Responce
    */
    public function index (Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $search = new AnnoncesSearch();
        $searchForm = $this->createForm(AnnoncesSearchType::class, $search);
        $searchForm->handleRequest($request);

        return $this->render('pages/annoncesAll.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest(),
            'annonces' => $this->repositoryAnnonces->paginateAllVisible($search, $request->query->getInt('page', 1)),
            'searchForm' => $searchForm->createView()
        ]);

    }

    /**
     * @Route("/annonces-premium", name="annonce.premium")
     * @return Responce
    */
    public function index2 (Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $search = new AnnoncesSearch();
        $searchForm = $this->createForm(AnnoncesSearchType::class, $search);
        $searchForm->handleRequest($request);

        return $this->render('pages/annoncesAll.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest(),
            'annonces' => $this->repositoryAnnonces->paginateAllVisiblePremium($search, $request->query->getInt('page', 1)),
            'searchForm' => $searchForm->createView()
        ]);

    }

    /**
     * @Route("/annonces/categorie/{category}", name="annonce.category")
     * @return Responce
    */
    public function index3 (Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('pages/annoncesCategory.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest(),
            'annoncesCat' => $annonces->getCatType(),
            'annonces' => $this->repositoryAnnonces->paginateAllVisibleCategory($annonces->getCategory(), $request->query->getInt('page', 1))
        ]);

    }

    /**
     * @Route("/annonce/{id}", name="annonce.show") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function show (Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, LikeAnnoncesRepository $repositoryLike, VoteUserRepository $repositoryVoteUser):Response{

        $author = $annonces->getAuthor();
        $authorId = $this->getUser();
        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if($annonces->getId() == $request->get('id'))
        {
            $annonces->setVisitor($annonces->getVisitor() + 1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonces);
            $entityManager->flush();
        }

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        if($this->getUser() != null)
        {
            $annoncesUsername = $this->getUser()->getUsername();
        }else{
            $annoncesUsername = null; 
        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView(),
                'annoncesUsername' => $annoncesUsername
            ]);

    
        }

        if($authorId == null)
        {
            $voteId = 0;
            $voteuserId = 0;
            $likeId = 0;
        }else{
            $voteId = $repositoryVote->findUserVote($authorId->getId());

            $voteuserId = $repositoryVoteUser->findUserVote($authorId->getId());

            $likeId = $repositoryLike->findUserLike($this->getUser()->getId());

        }

        $voteCount = $repositoryVote->findCount($annonces->getId());
        $voteuserCount = $repositoryVoteUser->findCount($annonces->getId());

        return $this->render('pages/annonce_type.html.twig', [
            'formContact' => $formContact->createView(),
            'annonces' => $annonces,
            'user' => $user,
            'count' => $countAnnonces,
            'annonceSimilaire' => $annonceSimilaire,
            'annonceLatest' => $annonceLatest,
            'countComment' => $countComment,
            'comment' => $comments,
            'formComment' => $formComment->createView(),
            'voteId' => $voteId,
            'voteCount' => $voteCount,
            'voteuserId' => $voteuserId,
            'voteuserCount' => $voteuserCount,
            'likeId' => $likeId,
            'annoncesUsername' => $annoncesUsername
        ]);

    }

    /**
     * @Route("/annonce/phone/{id}", name="annonce.phone") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function phone(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, LikeAnnoncesRepository $repositoryLike, VoteUserRepository $repositoryVoteUser):Response{

        if($annonces->getId() == $request->get('id'))
        {
            $annonces->setPhoneCount($annonces->getPhoneCount() + 1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonces);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonce.show', [
            'id' => $request->get('id')
        ]);

    }

    /**
     * @Route("/annonce/vote/stars/1/{id}", name="annonce.starsVote1") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote1(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteAnnonces();

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/2/{id}", name="annonce.starsVote2") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote2(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteAnnonces();

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/3/{id}", name="annonce.starsVote3") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote3(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteAnnonces();

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/4/{id}", name="annonce.starsVote4") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote4(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteAnnonces();

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/5/{id}", name="annonce.starsVote5") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote5(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteAnnonces();

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/like/{id}", name="annonce.like") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function like(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $like = new LikeAnnonces();

           
            $this->addFlash('success', 'Vous aimez cette annonce');
            $like->setUserId($this->getUser()->getId());
            $like->setAnnoncesId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($like);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/deposer-une-annonce", name="annonce.create")
     * @return Responce
    */
    public function create(Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $countMail = $repositoryMailbox->findCount($this->getUser()->getUsername());

        $annonces = new Annonces();
        $formAnnonces = $this->createForm(AnnoncesType::class, $annonces);
        $formAnnonces->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formAnnonces->isSubmitted() && $formAnnonces->isValid()) {

            $annonces->setAuthor($this->getUser()->getUsername());
            $annonces->setPremium(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonces);
            $entityManager->flush();
            
            return $this->render('user/confirmAnnonce.html.twig', [
                'formContact' => $formContact->createView(),
                'annonceLatest' => $this->repositoryAnnonces->findLatest(),
                'countMail' => $countMail
            ]);

        }

        return $this->render('pages/createAnnonce.html.twig', [
            'formContact' => $formContact->createView(),
            'formAnnonces' => $formAnnonces->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest(),
            'countMail' => $countMail  
        ]);

    }

     /**
     * @Route("/annonce/vote/stars/user/1/{id}", name="annonce.starsVote1b") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVoteb(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, UserRepository $repositoryUser)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteUser();
            $userId = $repositoryUser->findOneBy(array('username' => $annonces->getAuthor()));

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setUserId2($userId->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/user/2/{id}", name="annonce.starsVote2b") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote2b(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, UserRepository $repositoryUser)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteUser();
            $userId = $repositoryUser->findOneBy(array('username' => $annonces->getAuthor()));

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setUserId2($userId->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

     /**
     * @Route("/annonce/vote/stars/user/3/{id}", name="annonce.starsVote3b") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote3b(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, UserRepository $repositoryUser)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteUser();
            $userId = $repositoryUser->findOneBy(array('username' => $annonces->getAuthor()));

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setUserId2($userId->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/user/4/{id}", name="annonce.starsVote4b") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote4b(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, UserRepository $repositoryUser)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteUser();
            $userId = $repositoryUser->findOneBy(array('username' => $annonces->getAuthor()));

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setUserId2($userId->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/vote/stars/user/5/{id}", name="annonce.starsVote5b") requirements={"id": [0-9\-]""}
     * @return Responce
    */
    public function starsVote5b(Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, UserRepository $repositoryUser)
    {
        $author = $annonces->getAuthor();

        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        $user = $repository->findOneBy(array('username' => $author));

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
 if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->render('pages/annonce_type.html.twig', [
                'formContact' => $formContact->createView(),
                'annonces' => $annonces,
                'user' => $user,
                'count' => $countAnnonces,
                'annonceSimilaire' => $annonceSimilaire,
                'annonceLatest' => $annonceLatest,
                'countComment' => $countComment,
                'comment' => $comments,
                'formComment' => $formComment->createView()
            ]);  
        }

            $vote = new VoteUser();
            $userId = $repositoryUser->findOneBy(array('username' => $annonces->getAuthor()));

           
            $this->addFlash('success', 'Votre vote à été pris en compte');
            $vote->setUserId($this->getUser()->getId());
            $vote->setUserId2($userId->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('annonce.show', [ 'id' => $annonces->getId() ]);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annonce/edit/{id}", name="user.annonce.edit")
     * @return Responce
    */
    public function edit(Annonces $annonces, Request $request, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces, MailboxRepository $repositoryMailbox)
    {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $formAnnonces = $this->createForm(AnnoncesEditType::class, $annonces);
        $formAnnonces->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        if ($formAnnonces->isSubmitted() && $formAnnonces->isValid()) {

            if($this->getUser()->getCredits() > 4)
            {
                $credit = new Credits;
                $credit->setAmount(5);
                $credit->setDescription('Modification d\'une annonce');
                $credit->setStatus('validé');
                $credit->setUserId($this->getUser()->getId());
                $credit->setIdTrans(uniqid());
                $credits = $this->getUser()->getCredits();
                $this->getUser()->setCredits($credits - 5);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($credit);
                $entityManager->flush();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($annonces);
                $entityManager->flush();
                $this->addFlash('success', 'Votre annonce a été modifiée avec succès');
                return $this->redirectToRoute('user.annonces');

            }else{
                $this->addFlash('error', 'Vous n\'avez pas assez de crédit');
                return $this->redirectToRoute('user.credit');
            }

        }

        return $this->render('pages/editAnnonce.html.twig', [
            'formContact' => $formContact->createView(),
            'formAnnonces' => $formAnnonces->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest()  
        ]);

    }


     /**
     * @Route("/annonces/{author}", name="annoncesUser.show")
     * @return Responce
    */
    public function showUser (Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment, VoteAnnoncesRepository $repositoryVote, LikeAnnoncesRepository $repositoryLike, VoteUserRepository $repositoryVoteUser):Response{

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);


        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact, $annoncesMail);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $search = new AnnoncesSearch();
        $searchForm = $this->createForm(AnnoncesSearchType::class, $search);
        $searchForm->handleRequest($request);

        $author = $request->get('author');

        return $this->render('pages/annoncesUser.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $this->repositoryAnnonces->findLatest(),
            'annonces' => $this->repositoryAnnonces->paginateUser($annonces->getAuthor(), $search, $request->query->getInt('page', 1)),
            'searchForm' => $searchForm->createView(),
            'author' => $author
        ]);

    }

}