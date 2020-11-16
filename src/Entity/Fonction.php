<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FonctionRepository::class)
 */
class Fonction
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
    private $libelleFonction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleFonction(): ?string
    {
        return $this->libelleFonction;
    }

    public function setLibelleFonction(string $libelleFonction): self
    {
        $this->libelleFonction = $libelleFonction;

        return $this;
    }
}
