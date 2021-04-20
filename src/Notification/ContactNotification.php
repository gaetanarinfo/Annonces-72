<?php
namespace App\Notification;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class ContactNotification
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

    public function notify(Contact $contact)
    {   
        $message = (new \Swift_Message('Formulaire de contact - Annopnces-72'))
            ->setFrom($contact->getEmail())
            ->setTo('contact@annonces-72.fr')
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }

}

?>