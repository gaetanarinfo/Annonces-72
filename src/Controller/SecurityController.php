<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\RegisterType;
use App\Form\RecoverType;
use App\Form\RecoverNewType;
use App\Notification\ContactNotification;
use App\Notification\RecoverNotification;
use App\Notification\RegisterNotification;
use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     * @Security("not is_granted('ROLE_USER')")
     */
    public function login(AuthenticationUtils $authentificationUtils, SessionInterface $session, ContactNotification $notif, Request $request, AnnoncesRepository $repositoryAnnonces)
    {
        $error = $authentificationUtils->getLastAuthenticationError();
        $lastUsername = $authentificationUtils->getLastUsername();

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('security/login.html.twig', [
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @Security("not is_granted('ROLE_USER')")
     * @var UserPasswordEncoderInterface
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, RegisterNotification $register, SessionInterface $session, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->get('agreeTerms')->getData();
        $form->handleRequest($request);

        if($form->isEmpty())
        {
            $this->addFlash('error', 'Le formulaire ne peut pas être vide !');
            return $this->redirectToRoute('register');
        }

        $annoncesMail = $repositoryAnnonces->findLatestNonPremiumMail();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $token = substr(md5(uniqid(rand(80,80))), 0, 55);
            $session->set('token_user', $token);
            $user->setToken($session->get('token_user'));
            $register->notify($user, $annoncesMail);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Inscription réussie ! Merci de vérifier votre boite e-mail.');
            return $this->redirectToRoute('register');
        }

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annoncesPremium = $repositoryAnnonces->findLatestPremium();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('security/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annoncesPremium
        ]);

    }

    /**
     * @Route("/registerValidate/{token}", name="security.registerValidate")
     * @Security("not is_granted('ROLE_USER')")
     * @param User $user
     */
    public function registerValidate(User $user, string $token)
    {

        if($user->getToken() === $token){
            $user->setIsActive(1);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Compte valider avec succès');
            return $this->redirectToRoute('login');
        }

        return $this->redirectToRoute('login');
    }

    /**
     * @Security("not is_granted('ROLE_USER')")
     * @Route("/password_recover", name="password_recover")
     */
    public function passwordRecover(Request $request, RecoverNotification $recover, SessionInterface $session, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces)
    {
        
        $user = new User();
        $form = $this->createForm(RecoverType::class, $user);
        $form->handleRequest($request);

        if($form->isEmpty())
        {
            $this->addFlash('error', 'Le formulaire ne peut pas être vide !');
            return $this->redirectToRoute('password_recover');
        }

        if($form->isSubmitted())
        {
            $token = substr(md5(uniqid(rand(80,80))), 0, 55);
            $session->set('token_recover', $token);
            $session->set('user_email', $user->getEmail());
            $recover->notify($user, $token);
            $this->addFlash('success', 'Merci de vérifier votre boite e-mail.');
            return $this->redirectToRoute('password_recover');
        }

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonces = $repositoryAnnonces->findLatest();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('security/password_recover.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annonces
        ]);
    }

    /**
     * @Security("not is_granted('ROLE_USER')")
     * @Route("/recoverValidate/{token}", name="security.recoverValidate", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function recoverValidate(string $token, SessionInterface $session): Response
    {

        if($session->get('token_recover') === $token){
            return $this->redirectToRoute('passwordNew');
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Security("not is_granted('ROLE_USER')")
     * @Route("/passwordNew", name="passwordNew")
    * @var UserPasswordEncoderInterface
     */
    public function passwordNew(Request $request, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session, ContactNotification $notif, AnnoncesRepository $repositoryAnnonces)
    {
        $user = new User();
        $form = $this->createForm(RecoverNewType::class, $user);
        $form->handleRequest($request);

        if($form->isEmpty())
        {
            $this->addFlash('error', 'Le formulaire ne peut pas être vide !');
            return $this->redirectToRoute('login');
        }
        
        if($form->isSubmitted() && $form->isValid())
        {

            $email = $session->get('user_email');

            $entityManager = $this->getDoctrine()->getManager();
            $users = $entityManager->getRepository(User::class)
            ->findOneBy(array('email' => $email));
    
            if (!$users) {
                return $this->redirectToRoute('login');
            }
    
            $password = $passwordEncoder->encodePassword($users, $user->getPassword());
            $users->setPassword($password);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié !');
            return $this->redirectToRoute('login');
        }

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        $annonces = $repositoryAnnonces->findLatest();

        if ($formContact->isSubmitted() && $formContact->isValid()) {
           
            $notif->notify($contact);
            $this->addFlash('success', 'Votre message à bien été transmis');
            return $this->redirectToRoute('home');

        }

        return $this->render('security/passwordNew.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formContact' => $formContact->createView(),
            'annonceLatest' => $annonces
        ]);

    }

}

?>