<?php
namespace App\Notification;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class RegisterNotification
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

    public function notify(User $user)
    {   
        $message = (new \Swift_Message('Votre inscription - Annonces-72 '))
            ->setFrom('no-reply@annonces-72.fr')
            ->setTo($user->getEmail())
            ->setBody($this->renderer->render('emails/register.html.twig', [
                'register' => $user
            ]), 'text/html');
        $this->mailer->send($message);
    }

}

?>