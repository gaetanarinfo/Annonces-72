<?php
namespace App\Notification;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class CreateAnnonceNotification extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->session = $session;
    }

    public function notify(string $user, array $annonces, int $id, string $title)
    {   
        $message = (new \Swift_Message('Création d\'une annonce - Annonces-72'))
        ->setFrom('no-reply@annonces-72.fr')
        ->setTo($user)
            ->setBody($this->renderer->render('emails/createAnnonce.html.twig', [
                'annonces' => $annonces,
                'title' => $title,
                'id' => $id
            ]), 'text/html');
        $this->mailer->send($message);
    }

}

?>