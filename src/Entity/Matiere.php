<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle_matiere;

    /**
     * @ORM\Column(type="integer")
     */
    private $coefficient;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="matiere")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Classe::class, mappedBy="matieres")
     */
    private $classes;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $eventBackgroundColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $eventBorderColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $eventTextColor;

    /**
     * @ORM\OneToMany(targetEntity=EventPlanning::class, mappedBy="matieres")
     */
    private $eventPlannings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->classes = new ArrayCollection();
        $this->eventPlannings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleMatiere(): ?string
    {
        return $this->libelle_matiere;
    }

    public function setLibelleMatiere(string $libelle_matiere): self
    {
        $this->libelle_matiere = $libelle_matiere;

        return $this;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addMatiere($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeMatiere($this);
        }

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->addMatiere($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            $class->removeMatiere($this);
        }

        return $this;
    }

    public function getEventBackgroundColor(): ?string
    {
        return $this->eventBackgroundColor;
    }

    public function setEventBackgroundColor(?string $eventBackgroundColor): self
    {
        $this->eventBackgroundColor = $eventBackgroundColor;

        return $this;
    }

    public function getEventBorderColor(): ?string
    {
        return $this->eventBorderColor;
    }

    public function setEventBorderColor(?string $eventBorderColor): self
    {
        $this->eventBorderColor = $eventBorderColor;

        return $this;
    }

    public function getEventTextColor(): ?string
    {
        return $this->eventTextColor;
    }

    public function setEventTextColor(?string $eventTextColor): self
    {
        $this->eventTextColor = $eventTextColor;

        return $this;
    }

    /**
     * @return Collection|EventPlanning[]
     */
    public function getEventPlannings(): Collection
    {
        return $this->eventPlannings;
    }

    public function addEventPlanning(EventPlanning $eventPlanning): self
    {
        if (!$this->eventPlannings->contains($eventPlanning)) {
            $this->eventPlannings[] = $eventPlanning;
            $eventPlanning->setMatieres($this);
        }

        return $this;
    }

    public function removeEventPlanning(EventPlanning $eventPlanning): self
    {
        if ($this->eventPlannings->removeElement($eventPlanning)) {
            // set the owning side to null (unless already changed)
            if ($eventPlanning->getMatieres() === $this) {
                $eventPlanning->setMatieres(null);
            }
        }

        return $this;
    }
}
