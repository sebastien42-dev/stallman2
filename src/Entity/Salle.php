<?php

namespace App\Entity;

use App\Repository\SalleRepository;
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
}
