<?php
namespace App\Notification;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class RecoverNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(User $user, string $token)
    {
        $request = Request::createFromGlobals();
        $nom_jour_fr = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
        $mois_fr = Array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", 
                "septembre", "octobre", "novembre", "décembre");
        // on extrait la date du jour
        list($nom_jour, $jour, $mois, $annee) = explode('/', date("w/d/n/Y"));
        $heure = date('H:i:s');

        $message = (new \Swift_Message('Mot de passe oublié - AgenceWeb '))
            ->setFrom('noreply@agence-web.fr')
            ->setTo($user->getEmail())
            ->setBody($this->renderer->render('emails/recover_password.html.twig', [
                'recover' => $user,
                'date' => $nom_jour_fr[$nom_jour].' '.$jour.' '.$mois_fr[$mois].' '.$annee.' à '.$heure,
                'ip' => $request->getClientIp(),
                'token' => $token
            ]), 'text/html');
        $this->mailer->send($message);
    }

}

?>