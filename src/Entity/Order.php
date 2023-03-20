<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $orderPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $discount = null;

    #[ORM\Column]
    private ?float $fullPrice = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $userOrder = null;


    #[ORM\ManyToOne(inversedBy: 'orderCarrier')]
    private ?Carrier $carrier = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Address $addresses = null;

    #[ORM\Column]
    private ?int $orderQuantity = null;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrderDetail::class, cascade: ['persist'])]
    private Collection $orderDetails;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

  

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->orderDetails = new ArrayCollection([new OrderDetail()]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getOrderPrice(): ?float
    {
        return $this->orderPrice;
    }

    public function setOrderPrice(float $orderPrice): self
    {
        $this->orderPrice = $orderPrice;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getFullPrice(): ?float
    {
        return $this->fullPrice;
    }

    public function setFullPrice(float $fullPrice): self
    {
        $this->fullPrice = $fullPrice;

        return $this;
    }

    public function getUserOrder(): ?User
    {
        return $this->userOrder;
    }

    public function setUserOrder(?User $userOrder): self
    {
        $this->userOrder = $userOrder;

        return $this;
    }


    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function setCarrier(?Carrier $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddresses(): ?Address
    {
        return $this->addresses;
    }

    public function setAddresses(?Address $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function getOrderQuantity(): ?int
    {
        return $this->orderQuantity;
    }

    public function setOrderQuantity(int $orderQuantity): self
    {
        $this->orderQuantity = $orderQuantity;

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
            $orderDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrders() === $this) {
                $orderDetail->setOrders(null);
            }
        }

        return $this;
    }
    public function getDeleveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeleveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
