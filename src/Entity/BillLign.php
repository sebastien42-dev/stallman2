<?php

namespace App\Entity;

use App\Repository\BillLignRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BillLignRepository::class)
 */
class BillLign
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $globalLignValue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Bill::class, inversedBy="billLigns")
     */
    private $bill;

    /**
     * @ORM\ManyToOne(targetEntity=Package::class, inversedBy="billLigns")
     */
    private $package;

    /**
     * @ORM\ManyToOne(targetEntity=OutPackage::class, inversedBy="billLigns")
     */
    private $outPackage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getGlobalLignValue(): ?int
    {
        return $this->globalLignValue;
    }

    public function setGlobalLignValue(?int $globalLignValue): self
    {
        $this->globalLignValue = $globalLignValue;

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

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getOutPackage(): ?OutPackage
    {
        return $this->outPackage;
    }

    public function setOutPackage(?OutPackage $outPackage): self
    {
        $this->outPackage = $outPackage;

        return $this;
    }
}
