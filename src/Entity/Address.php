<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min : 2, max : 255)]
    private ?string $fullname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $street = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Regex(pattern : "/^[0-9]{5}$/")]
    private ?int $zipCode   = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max : 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max : 255)]
    private ?string $contry = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'addresses', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;



    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function __toString() {
        return $this->fullname . ', ' . ($this->company ? $this->company . ', ' : '') . $this->street . ', ' . $this->zipCode . ', ' . $this->city . ', ' . $this->contry;
    }
        


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

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

    public function getContry(): ?string
    {
        return $this->contry;
    }

    public function setContry(string $contry): self
    {
        $this->contry = $contry;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order) && $this->getDeletedAt() === null ){
            $this->orders->add($order);
        }

        return $this;
    }


    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }



}
