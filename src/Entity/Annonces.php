<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnoncesRepository::class)
 */
class Annonces
{
    const CAT_PRIMARY = [
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

    const CAT_VACANCES = [
        '0' => 'Locations & Gîtes',
        '1' => 'Chambres d\'hôtes',
        '2' => 'Campings',
        '3' => 'Hébergements insolites',
        '4' => 'Hôtels'
    ];

    const CAT_EMPLOI = [
        '0' => 'Offres d\'emploi',
        '1' => 'Formations Professionnelles'
    ];

    const CAT_VEHICULES = [
        '0' => 'Voitures',
        '1' => 'Motos',
        '2' => 'Caravaning',
        '3' => 'Utilitaires',
        '4' => 'Nautisme',
        '5' => 'Équipement auto',
        '6' => 'Équipement moto',
        '7' => 'Équipement caravaning',
        '8' => 'Équipement nautisme'
    ];

    const CAT_IMMOBILIER = [
        '0' => 'Ventes immobilières',
        '1' => 'Locations',
        '2' => 'Colocations',
        '3' => 'Bureaux & Commerces'
    ];

    const CAT_MODE = [
        '0' => 'Vêtements',
        '1' => 'Chaussures',
        '2' => 'Accessoires & Bagagerie',
        '3' => 'Montres & Bijoux',
        '4' => 'Équipement bébé',
        '5' => 'Vêtements bébé'
    ];

    const CAT_MAISON = [
        '0' => 'Ameublement',
        '1' => 'Électroménager',
        '2' => 'Arts de la table',
        '3' => 'Décoration',
        '4' => 'Linge de maison',
        '5' => 'Bricolage',
        '6' => 'Jardinage'
    ];
    
    const CAT_MULTIMEDIA = [
        '0' => 'Informatique',
        '1' => 'Consoles & Jeux vidéo',
        '2' => 'Image & Son',
        '3' => 'Téléphonie'
    ];

    const CAT_lOISIRS = [
        '0' => 'DVD - Films',
        '1' => 'CD - Musique',
        '2' => 'Image & Son',
        '3' => 'Livres',
        '4' => 'Vélos',
        '5' => 'Sports & Hobbies',
        '6' => 'Instruments de musique',
        '7' => 'Collection',
        '8' => 'Jeux & Jouets',
        '9' => 'Vins & Gastronomie'
    ];

    const CAT_ANIMAUX = [
        '0' => 'Animaux',
    ];

    const CAT_METERIEL_PROFESSIONNEL = [
        '0' => 'Matériel agricole',
        '1' => 'Transport - Manutention',
        '2' => 'BTP - Chantier gros-oeuvre',
        '3' => 'Outillage - Matériaux 2nd-oeuvre',
        '4' => 'Équipements industriels',
        '5' => 'Restauration - Hôtellerie',
        '6' => 'Fournitures de bureau',
        '7' => 'Commerces & Marchés',
        '8' => 'Matériel médical'
    ];

    const CAT_SERVICES = [
        '0' => 'Prestations de services',
        '1' => 'Billetterie',
        '2' => 'Évènements',
        '3' => 'Cours particuliers',
        '4' => 'Covoiturage'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      min = 10,
     *      max = 100,
     *      minMessage = "10 caractères minimum",
     *      maxMessage = "100 caractères maximum"
     *      )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\Length(
     *      min = 50,
     *      max = 120,
     *      minMessage = "50 caractères minimum",
     *      maxMessage = "120 caractères maximum"
     *      )
     */
    private $smallContent;

    /**
     * @ORM\Column(type="text")
     */
    private $largeContent;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $premium;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;
    
    /**
     * @var Picture|null
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="annonces", orphanRemoval=true, cascade={"persist"})
     */
    private $pictures;

    /**
     * @Assert\All({
     *   @Assert\Image(mimeTypes="image/*")
     * })
     */
    private $pictureFiles;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $sousCategory;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 2,
     *      max = 1000000,
     *      minMessage = "Le prix n'est pas assez élevé",
     *      maxMessage = "Le prix dépasse"
     *      )
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAtPremium;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $terminedAtPremium;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=10)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\Column(type="float", scale=4, precision=6)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", scale=4, precision=7)
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @Assert\Regex("/^[0-9]{5}$/")
     * @ORM\Column(type="string", length=255)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visitor;

    /**
     * @ORM\Column(type="integer")
     */
    private $phoneCount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $topList;

    public function __construct()
    {
        $this->title = "";
        $this->author = "";
        $this->smallContent = "";
        $this->largeContent = "";
        $this->category = 0;
        $this->sousCategory = 0;
        $this->premium = 0;
        $this->isValid = 0;
        $this->price = 0;
        $this->pictures = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->visitor = 0;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSmallContent(): ?string
    {
        return $this->smallContent;
    }

    public function setSmallContent(string $smallContent): self
    {
        $this->smallContent = $smallContent;

        return $this;
    }

    public function getLargeContent(): ?string
    {
        return $this->largeContent;
    }

    public function setLargeContent(string $largeContent): self
    {
        $this->largeContent = $largeContent;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection|PictureAppartementA[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(Picture $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAnnonces($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getAnnonces() === $this) {
                $picture->setAnnonces(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * @param mixed $pictureFiles
     * @return AppartementA
     */
    public function setPictureFiles($pictureFiles): self
    {
        foreach($pictureFiles as $pictureFile) {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
        }
        $this->pictureFiles = $pictureFiles;
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

    public function getCatType(): string
    {
        return self::CAT_PRIMARY[$this->category];
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

    public function getCatType2(): string
    {
        if($this->category = 'Vacances'){
            return self::CAT_VACANCES[$this->sousCategory];
        }else if($this->category = 'Emploi'){
            return self::CAT_EMPLOI[$this->sousCategory];
        }else if($this->category = 'Véhicules'){
            return self::CAT_VEHICULES[$this->sousCategory];
        }else if($this->category = 'Immobilier'){
            return self::CAT_IMMOBILIER[$this->sousCategory];
        }else if($this->category = 'Mode'){
            return self::CAT_MODE[$this->sousCategory];
        }else if($this->category = 'Maison'){
            return self::CAT_MAISON[$this->sousCategory];
        }else if($this->category = 'Multimédia'){
            return self::CAT_MULTIMEDIA[$this->sousCategory];
        }else if($this->category = 'Loisirs'){
            return self::CAT_lOISIRS[$this->sousCategory];
        }else if($this->category = 'Animaux'){
            return self::CAT_lOISIRS[$this->sousCategory];
        }else if($this->category = 'Animaux'){
            return self::CAT_ANIMAUX[$this->sousCategory];
        }else if($this->category = 'Matériel Professionnel'){
            return self::CAT_METERIEL_PROFESSIONNEL[$this->sousCategory];
        }else if($this->category = 'Services'){
            return self::CAT_SERVICES[$this->sousCategory];
        }
        
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getCreatedAtPremium(): ?\DateTimeInterface
    {
        return $this->createdAtPremium;
    }

    public function setCreatedAtPremium(?\DateTimeInterface $createdAtPremium): self
    {
        $this->createdAtPremium = $createdAtPremium;

        return $this;
    }

    public function getTerminedAtPremium(): ?\DateTimeInterface
    {
        return $this->terminedAtPremium;
    }

    public function setTerminedAtPremium(?\DateTimeInterface $terminedAtPremium): self
    {
        $this->terminedAtPremium = $terminedAtPremium;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getVisitor(): ?int
    {
        return $this->visitor;
    }

    public function setVisitor(?int $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getPhoneCount(): ?int
    {
        return $this->phoneCount;
    }

    public function setPhoneCount(int $phoneCount): self
    {
        $this->phoneCount = $phoneCount;

        return $this;
    }

    public function getTopList(): ?\DateTimeInterface
    {
        return $this->topList;
    }

    public function setTopList(?\DateTimeInterface $topList): self
    {
        $this->topList = $topList;

        return $this;
    }

}
