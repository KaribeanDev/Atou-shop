<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('productName')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $productName = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $productDescription = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    #[Assert\NotNull()]
    #[Assert\GreaterThan(value: 0)]
    private ?float $productPrice = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;


    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'products')]
    private Collection $user;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Category $category = null;


    #[ORM\Column(nullable: true)]
    private ?int $mostViewed = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: WishList::class)]
    private Collection $wishLists;

    #[ORM\Column(nullable: true)]
    private ?float $discount = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetail::class)]
    private Collection $orderDetails;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;



    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSlug(): void
    {
        $this->slug = $this->generateSlug();
    }

    private function generateSlug(): string
    {

        $slugify = new Slugify();
        return $slugify->slugify($this->productName);
    }



    public function __toString()
    {
        return $this->productName;
    }


    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }


    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): self
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }



    public function getMostViewed(): ?int
    {
        return $this->mostViewed;
    }

    public function setMostViewed(?int $mostViewed): self
    {
        $this->mostViewed = $mostViewed;

        return $this;
    }

    /**
     * @return Collection<int, WishList>
     */
    public function getUserId(): Collection
    {
        return $this->wishLists;
    }

    public function addUserId(WishList $wishLists): self
    {
        if (!$this->wishLists->contains($wishLists)) {
            $this->wishLists->add($wishLists);
            $wishLists->setProduct($this);
        }

        return $this;
    }

    public function removeUserId(WishList $wishLists): self
    {
        if ($this->wishLists->removeElement($wishLists)) {
            // set the owning side to null (unless already changed)
            if ($wishLists->getProduct() === $this) {
                $wishLists->setProduct(null);
            }
        }

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
