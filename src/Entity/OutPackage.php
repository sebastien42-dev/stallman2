<?php

namespace App\Entity;

use App\Repository\OutPackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutPackageRepository::class)
 */
class OutPackage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $outPackageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Proof::class, inversedBy="outPackages")
     */
    private $proof;

    /**
     * @ORM\OneToMany(targetEntity=BillLign::class, mappedBy="outPackage")
     */
    private $billLigns;

    public function __construct()
    {
        $this->billLigns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutPackageName(): ?string
    {
        return $this->outPackageName;
    }

    public function setOutPackageName(?string $outPackageName): self
    {
        $this->outPackageName = $outPackageName;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getProof(): ?Proof
    {
        return $this->proof;
    }

    public function setProof(?Proof $proof): self
    {
        $this->proof = $proof;

        return $this;
    }

    /**
     * @return Collection|BillLign[]
     */
    public function getBillLigns(): Collection
    {
        return $this->billLigns;
    }

    public function addBillLign(BillLign $billLign): self
    {
        if (!$this->billLigns->contains($billLign)) {
            $this->billLigns[] = $billLign;
            $billLign->setOutPackage($this);
        }

        return $this;
    }

    public function removeBillLign(BillLign $billLign): self
    {
        if ($this->billLigns->removeElement($billLign)) {
            // set the owning side to null (unless already changed)
            if ($billLign->getOutPackage() === $this) {
                $billLign->setOutPackage(null);
            }
        }

        return $this;
    }
}
