<?php

namespace App\Entity;

use App\Repository\RelationUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelationUserRepository::class)
 */
class RelationUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="collegue")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="collegueRequest")
     */
    private $requestUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pending;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAccepted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRequestUser(): ?User
    {
        return $this->requestUser;
    }

    public function setRequestUser(?User $requestUser): self
    {
        $this->requestUser = $requestUser;

        return $this;
    }

    public function getPending(): ?bool
    {
        return $this->pending;
    }

    public function setPending(bool $pending): self
    {
        $this->pending = $pending;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }
}
