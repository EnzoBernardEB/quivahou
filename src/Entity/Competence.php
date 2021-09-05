<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="competences")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $categorie_id;

    /**
     * @ORM\ManyToMany(targetEntity=Experience::class, mappedBy="competence_utilise")
     */
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity=UserHasCompetence::class, mappedBy="competence")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $userHasCompetences;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->userHasCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getNom();
    }

    public function getCategorieId(): ?Categorie
    {
        return $this->categorie_id;
    }


    public function setCategorieId(?Categorie $categorie_id): self
    {
        $this->categorie_id = $categorie_id;

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->addCompetenceUtilise($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            $experience->removeCompetenceUtilise($this);
        }

        return $this;
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

    /**
     * @return Collection|UserHasCompetence[]
     */
    public function getUserHasCompetences(): Collection
    {
        return $this->userHasCompetences;
    }

    public function addUserHasCompetence(UserHasCompetence $userHasCompetence): self
    {
        if (!$this->userHasCompetences->contains($userHasCompetence)) {
            $this->userHasCompetences[] = $userHasCompetence;
            $userHasCompetence->setCompetence($this);
        }

        return $this;
    }

    public function removeUserHasCompetence(UserHasCompetence $userHasCompetence): self
    {
        if ($this->userHasCompetences->removeElement($userHasCompetence)) {
            // set the owning side to null (unless already changed)
            if ($userHasCompetence->getCompetence() === $this) {
                $userHasCompetence->setCompetence(null);
            }
        }

        return $this;
    }
}
