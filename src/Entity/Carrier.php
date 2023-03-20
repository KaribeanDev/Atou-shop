<?php

namespace App\Entity;

use App\Repository\CarrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarrierRepository::class)]
class Carrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $carrierName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $carrierDescript = null;

    #[ORM\Column(length: 255)]
    private ?string $carrierImage = null;

    #[ORM\Column]
    private ?float $carrierPrice = null;

    #[ORM\OneToMany(mappedBy: 'carrier', targetEntity: Order::class)]
    private Collection $orderCarrier;

    public function __toString()
    {
        return $this->carrierName . ', '  . ($this->carrierPrice/100) . '€' ;
    }
        

    public function __construct()
    {
        $this->orderCarrier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    public function getCarrierDescript(): ?string
    {
        return $this->carrierDescript;
    }

    public function setCarrierDescript(string $carrierDescript): self
    {
        $this->carrierDescript = $carrierDescript;

        return $this;
    }

    public function getCarrierImage(): ?string
    {
        return $this->carrierImage;
    }

    public function setCarrierImage(string $carrierImage): self
    {
        $this->carrierImage = $carrierImage;

        return $this;
    }

    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    public function setCarrierPrice(float $carrierPrice): self
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderCarrier(): Collection
    {
        return $this->orderCarrier;
    }

    public function addOrderCarrier(Order $orderCarrier): self
    {
        if (!$this->orderCarrier->contains($orderCarrier)) {
            $this->orderCarrier->add($orderCarrier);
            $orderCarrier->setCarrier($this);
        }

        return $this;
    }

    public function removeOrderCarrier(Order $orderCarrier): self
    {
        if ($this->orderCarrier->removeElement($orderCarrier)) {
            // set the owning side to null (unless already changed)
            if ($orderCarrier->getCarrier() === $this) {
                $orderCarrier->setCarrier(null);
            }
        }

        return $this;
    }
}
