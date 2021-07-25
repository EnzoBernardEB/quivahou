<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie_id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="competence_id")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Experience::class, mappedBy="competence_utilise")
     */
    private $experiences;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->experiences = new ArrayCollection();
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
            $user->addCompetenceId($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeCompetenceId($this);
        }

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
}
