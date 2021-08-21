<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
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
     * @ORM\Column(type="text")
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="experiences")
     */
    private $competence_utilise;

    /**
     * @ORM\ManyToOne(targetEntity=TypeMission::class, inversedBy="experiences")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="experience")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="experiences")
     */
    private $entreprise;


    public function __construct()
    {
        $this->competence_utilise = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetenceUtilise(): Collection
    {
        return $this->competence_utilise;
    }

    public function addCompetenceUtilise(Competence $competenceUtilise): self
    {
        if (!$this->competence_utilise->contains($competenceUtilise)) {
            $this->competence_utilise[] = $competenceUtilise;
        }

        return $this;
    }

    public function removeCompetenceUtilise(Competence $competenceUtilise): self
    {
        $this->competence_utilise->removeElement($competenceUtilise);

        return $this;
    }

    public function getType(): ?TypeMission
    {
        return $this->type;
    }
    public function __toString(): string
    {
        return $this->getType();
    }

    public function setType(?TypeMission $type): self
    {
        $this->type = $type;

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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
    
}
