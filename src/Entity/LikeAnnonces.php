<?php

namespace App\Entity;

use App\Repository\LikeAnnoncesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeAnnoncesRepository::class)
 */
class LikeAnnonces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $annoncesId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAnnoncesId(): ?int
    {
        return $this->annoncesId;
    }

    public function setAnnoncesId(int $annoncesId): self
    {
        $this->annoncesId = $annoncesId;

        return $this;
    }
}
