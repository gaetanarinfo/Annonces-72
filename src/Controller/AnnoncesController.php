<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AnnoncesController extends AbstractController
{

    /**
     * @Route("/annonce", name="annonce")
     * @return Responce
    */

    public function index (Request $request):Response{

        return $this->render('pages/annonce_type.html.twig');

    }

}
