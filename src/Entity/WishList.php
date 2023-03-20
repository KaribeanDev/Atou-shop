<?php

namespace App\Entity;

use App\Repository\WishListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishListRepository::class)]
class WishList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'wishLists')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'wishLists')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $wishlistDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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

    public function getWishlistDate(): ?\DateTimeImmutable
    {
        return $this->wishlistDate;
    }

    public function setWishlistDate(\DateTimeImmutable $wishlistDate): self
    {
        $this->wishlistDate = $wishlistDate;

        return $this;
    }
}
