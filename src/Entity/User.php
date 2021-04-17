<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator\Constraints as AppAssert;
use App\Validator\Constraints as AppAssertMobile;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface,\Serializable
{

    const ACTIVE = [
        0 => 'No',
        1 => 'Yes'
    ];

    const GENDER = [
        0 => 'Male',
        1 => 'Female'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=10)
     * @Assert\NotBlank()
     * @AppAssert\Telephone()
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="mobile", type="string", length=10)
     * @Assert\NotBlank()
     * @AppAssertMobile\TelephoneMobile()
     */
    private $mobile;

    /**
     * @var Avatar|null
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avatar", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $pictures;

    /**
     * @Assert\All({
     *   @Assert\Image(mimeTypes="image/*")
     * })
     */
    private $pictureFiles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $credits;


     public function __construct()
     {
         $this->createDate = new \DateTime();
         $this->roles = array('ROLE_USER');
         $this->isActive = 0;
         $this->token = "";
         $this->updated_at = new \DateTime();
         $this->pictures = new ArrayCollection();
         $this->lastname = 'Aucun nom';
         $this->firstname = 'Aucun prénom';
         $this->address = 'Aucune adresse';
         $this->postalCode = 0;
         $this->gender = 0;
         $this->city = 'Aucune ville';
         $this->country = 'FR';
         $this->phone = '0200000000';
         $this->mobile = '0600000000'; 
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    public function getRoles()
{
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
}

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles,
            $this->createDate,
            $this->isActive,
            $this->token,
            $this->lastname,
            $this->firstname,
            $this->gender,
            $this->postalCode,
            $this->city,
            $this->address,
            $this->country,
            $this->phone,
            $this->mobile
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles,
            $this->createDate,
            $this->isActive,
            $this->token,
            $this->lastname,
            $this->firstname,
            $this->gender,
            $this->postalCode,
            $this->city,
            $this->address,
            $this->country,
            $this->phone,
            $this->mobile
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

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

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     * @param string $phone
    */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile(): ?int
    {
        return $this->mobile;
    }

     /**
     * Set mobile
     * @param string $mobile
    */
    public function setMobile(int $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Avatar[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getPicture(): ?Avatar
    {
        return $this->picture;
    }

    public function setPicture(Avatar $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    public function addPicture(Avatar $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setUser($this);
        }

        return $this;
    }

    public function removePicture(Avatar $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getUser() === $this) {
                $picture->setUser(null);
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
     * @return User
     */
    public function setPictureFiles($pictureFiles): self
    {
        $picture = new Avatar();
        $picture->setImageFile($pictureFiles);
        $this->addPicture($picture);
        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

}
