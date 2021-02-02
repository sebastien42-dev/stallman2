<?php

namespace App\Entity;

use App\Repository\BillRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BillRepository::class)
 */
class Bill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billProviderNum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $globalBillValue;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bills")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=billState::class, inversedBy="bills")
     */
    private $billState;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBillProviderNum(): ?string
    {
        return $this->billProviderNum;
    }

    public function setBillProviderNum(?string $billProviderNum): self
    {
        $this->billProviderNum = $billProviderNum;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getGlobalBillValue(): ?int
    {
        return $this->globalBillValue;
    }

    public function setGlobalBillValue(?int $globalBillValue): self
    {
        $this->globalBillValue = $globalBillValue;

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

    public function getBillState(): ?billState
    {
        return $this->billState;
    }

    public function setBillState(?billState $billState): self
    {
        $this->billState = $billState;

        return $this;
    }
}
