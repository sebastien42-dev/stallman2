<?php

namespace App\Entity;

use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PackageRepository::class)
 */
class Package
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=355, nullable=true)
     */
    private $packageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=BillLign::class, mappedBy="package")
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

    public function getPackageName(): ?string
    {
        return $this->packageName;
    }

    public function setPackageName(?string $packageName): self
    {
        $this->packageName = $packageName;

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
            $billLign->setPackage($this);
        }

        return $this;
    }

    public function removeBillLign(BillLign $billLign): self
    {
        if ($this->billLigns->removeElement($billLign)) {
            // set the owning side to null (unless already changed)
            if ($billLign->getPackage() === $this) {
                $billLign->setPackage(null);
            }
        }

        return $this;
    }
}
