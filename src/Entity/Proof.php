<?php

namespace App\Entity;

use App\Repository\ProofRepository;
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
}
