<?php

namespace App\Controller\User;

use App\Entity\Avatar;
use App\Entity\User;
use App\Form\UserType3;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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

    public function index()
    {
        $username = $this->getUser('username')->getUsername();

        return $this->render('user/index.html.twig', [

        ]);
    }

    /**
     * @Route("/profil/edit", name="user.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType3::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                $user->setUpdatedAt(new \DateTime('now'));
                $this->em->flush();
                $this->addFlash('success', 'Profil modifié avec succès.');
                return $this->redirectToRoute('profil');      
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
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

}

?>