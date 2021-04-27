<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Length(
     *      min = 1,
     *      max = 500,
     *      minMessage = "Vous devez saisir au moins {{ limit }} caractère",
     *      maxMessage = "Vous ne pouvez pas saisir plus de {{ limit }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type("string")
     */
    private $dateSend;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @MaxDepth(1)
     */
    private $userFrom;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @MaxDepth(1)
     */
    private $userTo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isImportant;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("bool")
     */
    private $isRead;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDateSend(): ?\DateTimeInterface
    {
        return $this->dateSend;
    }

    public function setDateSend(?\DateTimeInterface $dateSend): self
    {
        $this->dateSend = $dateSend;

        return $this;
    }

    public function getUserFrom(): ?User
    {
        return $this->userFrom;
    }

    public function setUserFrom(?User $userFrom): self
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    public function getUserTo(): ?User
    {
        return $this->userTo;
    }

    public function setUserTo(?User $userTo): self
    {
        $this->userTo = $userTo;

        return $this;
    }

    public function getIsImportant(): ?bool
    {
        return $this->isImportant;
    }

    public function setIsImportant(bool $isImportant): self
    {
        $this->isImportant = $isImportant;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
