<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
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
    private $libelleNote;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="float")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="notes")
     */
    private $matieres;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notes")
     */
    private $eleves;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $coefficient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleNote(): ?string
    {
        return $this->libelleNote;
    }

    public function setLibelleNote(?string $libelleNote): self
    {
        $this->libelleNote = $libelleNote;

        return $this;
    }

    public function getDateNote(): ?\DateTimeInterface
    {
        return $this->dateNote;
    }

    public function setDateNote(?\DateTimeInterface $dateNote): self
    {
        $this->dateNote = $dateNote;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMatieres(): ?Matiere
    {
        return $this->matieres;
    }

    public function setMatieres(?Matiere $matieres): self
    {
        $this->matieres = $matieres;

        return $this;
    }

    public function getEleves(): ?User
    {
        return $this->eleves;
    }

    public function setEleves(?User $eleves): self
    {
        $this->eleves = $eleves;

        return $this;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(?int $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }
}
