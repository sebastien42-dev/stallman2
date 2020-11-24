<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
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
    private $libelleSalle;

    /**
     * @ORM\OneToMany(targetEntity=EventPlanning::class, mappedBy="salles")
     */
    private $eventPlannings;

    public function __construct()
    {
        $this->eventPlannings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleSalle(): ?string
    {
        return $this->libelleSalle;
    }

    public function setLibelleSalle(string $libelleSalle): self
    {
        $this->libelleSalle = $libelleSalle;

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
            $eventPlanning->setSalles($this);
        }

        return $this;
    }

    public function removeEventPlanning(EventPlanning $eventPlanning): self
    {
        if ($this->eventPlannings->removeElement($eventPlanning)) {
            // set the owning side to null (unless already changed)
            if ($eventPlanning->getSalles() === $this) {
                $eventPlanning->setSalles(null);
            }
        }

        return $this;
    }
}
