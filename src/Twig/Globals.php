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

    public function tags()
    {
        return [
            '0' => 'Vacances',
            '1' => 'Emploi',
            '2' => 'Véhicules',
            '3' => 'Immobilier',
            '4' => 'Mode',
            '5' => 'Maison',
            '6' => 'Multimédia',
            '7' => 'Loisirs',
            '8' => 'Animaux',
            '9' => 'Matériel Professionnel',
            '10' => 'Services'
        ];

    }


    
}