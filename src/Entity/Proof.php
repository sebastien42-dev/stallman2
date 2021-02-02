<?php

namespace App\Entity;

use App\Repository\ProofRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProofRepository::class)
 */
class Proof
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
    private $proofFile;

    /**
     * @ORM\OneToMany(targetEntity=OutPackage::class, mappedBy="proof")
     */
    private $outPackages;

    public function __construct()
    {
        $this->outPackages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProofFile(): ?string
    {
        return $this->proofFile;
    }

    public function setProofFile(?string $proofFile): self
    {
        $this->proofFile = $proofFile;

        return $this;
    }

    /**
     * @return Collection|OutPackage[]
     */
    public function getOutPackages(): Collection
    {
        return $this->outPackages;
    }

    public function addOutPackage(OutPackage $outPackage): self
    {
        if (!$this->outPackages->contains($outPackage)) {
            $this->outPackages[] = $outPackage;
            $outPackage->setProof($this);
        }

        return $this;
    }

    public function removeOutPackage(OutPackage $outPackage): self
    {
        if ($this->outPackages->removeElement($outPackage)) {
            // set the owning side to null (unless already changed)
            if ($outPackage->getProof() === $this) {
                $outPackage->setProof(null);
            }
        }

        return $this;
    }
}
