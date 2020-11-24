<?php

namespace App\Entity;

use App\Repository\EventPlanningRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventPlanningRepository::class)
 */
class EventPlanning
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
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="eventPlannings")
     */
    private $salles;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="eventPlannings")
     */
    private $classes;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="eventPlannings")
     */
    private $matieres;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventPlannings")
     */
    private $formateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getSalles(): ?Salle
    {
        return $this->salles;
    }

    public function setSalles(?Salle $salles): self
    {
        $this->salles = $salles;

        return $this;
    }

    public function getClasses(): ?Classe
    {
        return $this->classes;
    }

    public function setClasses(?Classe $classes): self
    {
        $this->classes = $classes;

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

    public function getFormateur(): ?User
    {
        return $this->formateur;
    }

    public function setFormateur(?User $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }
}
