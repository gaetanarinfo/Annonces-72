<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\AnnoncesRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AnnoncesController extends AbstractController
{

    /**
     * @Route("/annonce/{id}", name="annonce.show") requirements={"id": [0-9\-]""}
     * @return Responce
    */

    public function show (Annonces $annonces, Request $request, ContactNotification $notif, UserRepository $repository, AnnoncesRepository $repositoryAnnonces, CommentRepository $repositoryComment):Response{

        $author = $annonces->getAuthor();
        $countAnnonces = $repositoryAnnonces->findCount($author);
        $annonceSimilaire = $repositoryAnnonces->findSimilaire($annonces->getCategory());
        $annonceLatest = $repositoryAnnonces->findLatest();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $user = $repository->findOneBy(array('username' => $author));

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);
        $comments = $repositoryComment->findLatest($annonces->getId());
        $countComment = $repositoryComment->findCount($annonces->getId());

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($author);
            $comment->setAnnonceId($annonces->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à été poster');
            return $this->redirectToRoute('home');

    
        }

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

}
