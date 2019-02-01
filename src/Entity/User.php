<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @property string api_key
 */
class User
{
    /**
     * @Groups("user")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $apiKey;

    /**
     * @Groups("user")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @Groups("user")
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="user", orphanRemoval=true)
     */
    private $cards;

    /**
     * @Groups("user")
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subscription;

    /**
     * @Groups("user")
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $roles = [];

    public function __construct()
    {

        $this->cards = new ArrayCollection();
        $this->apiKey = uniqid('app', true);
        $this->roles = array('ROLE_USER');
        $this->createdAt = \DateTime::createFromFormat('U',time(), new \DateTimeZone('CET'));
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setUser($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getUser() === $this) {
                $card->setUser(null);
            }
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
