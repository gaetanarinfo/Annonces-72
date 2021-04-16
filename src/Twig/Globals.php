<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;

class Globals {

    /**
     *
     * @var EntityManagerInterface
     */

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    
}