<?php

namespace App\Entity;

use App\Repository\OutPackageRepository;
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
}
