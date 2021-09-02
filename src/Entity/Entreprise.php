<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
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
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="entreprise")
     */
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity=MissionEnCours::class, mappedBy="entreprise", orphanRemoval=false)
     */
    private $missionEnCours;
    

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->missionEnCours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function __toString(): string
    {
        return $this->getNom();
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $experience->setEntreprise($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getEntreprise() === $this) {
                $experience->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MissionEnCours[]
     */
    public function getMissionEnCours(): Collection
    {
        return $this->missionEnCours;
    }

    public function addMissionEnCour(MissionEnCours $missionEnCour): self
    {
        if (!$this->missionEnCours->contains($missionEnCour)) {
            $this->missionEnCours[] = $missionEnCour;
            $missionEnCour->setEntreprise($this);
        }

        return $this;
    }

    public function removeMissionEnCour(MissionEnCours $missionEnCour): self
    {
        if ($this->missionEnCours->removeElement($missionEnCour)) {
            // set the owning side to null (unless already changed)
            if ($missionEnCour->getEntreprise() === $this) {
                $missionEnCour->setEntreprise(null);
            }
        }

        return $this;
    }


}
