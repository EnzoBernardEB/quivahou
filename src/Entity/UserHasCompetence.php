<?php

namespace App\Entity;

use App\Repository\UserHasCompetenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserHasCompetenceRepository::class)
 */
class UserHasCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $maitrise;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLiked;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userHasCompetences")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="userHasCompetences")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $competence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaitrise(): ?string
    {
        return $this->maitrise;
    }

    public function setMaitrise(string $maitrise): self
    {
        $this->maitrise = $maitrise;

        return $this;
    }

    public function getIsLiked(): ?bool
    {
        return $this->isLiked;
    }

    public function setIsLiked(bool $isLiked): self
    {
        $this->isLiked = $isLiked;

        return $this;
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

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }
    public function __toString(): string
    {
        return $this->getCompetence();
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }
}
