<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
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
    private $libelleClasse;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="classes")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, inversedBy="classes")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=EventPlanning::class, mappedBy="classes")
     */
    private $eventPlannings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->eventPlannings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleClasse(): ?string
    {
        return $this->libelleClasse;
    }

    public function setLibelleClasse(string $libelleClasse): self
    {
        $this->libelleClasse = $libelleClasse;

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
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        $this->matieres->removeElement($matiere);

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
            $eventPlanning->setClasses($this);
        }

        return $this;
    }

    public function removeEventPlanning(EventPlanning $eventPlanning): self
    {
        if ($this->eventPlannings->removeElement($eventPlanning)) {
            // set the owning side to null (unless already changed)
            if ($eventPlanning->getClasses() === $this) {
                $eventPlanning->setClasses(null);
            }
        }

        return $this;
    }
}
