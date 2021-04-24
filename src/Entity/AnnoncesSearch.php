<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

class AnnoncesSearch 
{

    /**
     * @var string|null
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sousCategory;

    public function __construct()
    {
        $this->title = '';
        $this->category = 0;
        $this->sousCategory = 0;
    }

    /**
     * Get the value of title
     *
     * @return  string|null
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string|null  $title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSousCategory(): ?int
    {
        return $this->sousCategory;
    }

    public function setSousCategory(int $sousCategory): self
    {
        $this->sousCategory = $sousCategory;

        return $this;
    }

}

?>