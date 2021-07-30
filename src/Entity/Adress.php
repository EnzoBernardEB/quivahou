<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdressRepository::class)
 */
class Adress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_fantoir;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rep;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_voie;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_postal;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_insee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_commune;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_insee_ancienne_commune;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_ancienne_commune;

    /**
     * @ORM\Column(type="float")
     */
    private $x;

    /**
     * @ORM\Column(type="float")
     */
    private $y;

    /**
     * @ORM\Column(type="float")
     */
    private $lon;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_ld;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle_acheminement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_afnor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source_nom_voie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFantoir(): ?string
    {
        return $this->id_fantoir;
    }

    public function setIdFantoir(?string $id_fantoir): self
    {
        $this->id_fantoir = $id_fantoir;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getRep(): ?string
    {
        return $this->rep;
    }

    public function setRep(?string $rep): self
    {
        $this->rep = $rep;

        return $this;
    }

    public function getNomVoie(): ?string
    {
        return $this->nom_voie;
    }

    public function setNomVoie(string $nom_voie): self
    {
        $this->nom_voie = $nom_voie;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getCodeInsee(): ?int
    {
        return $this->code_insee;
    }

    public function setCodeInsee(int $code_insee): self
    {
        $this->code_insee = $code_insee;

        return $this;
    }

    public function getNomCommune(): ?string
    {
        return $this->nom_commune;
    }

    public function setNomCommune(string $nom_commune): self
    {
        $this->nom_commune = $nom_commune;

        return $this;
    }

    public function getCodeInseeAncienneCommune(): ?string
    {
        return $this->code_insee_ancienne_commune;
    }

    public function setCodeInseeAncienneCommune(?string $code_insee_ancienne_commune): self
    {
        $this->code_insee_ancienne_commune = $code_insee_ancienne_commune;

        return $this;
    }

    public function getNomAncienneCommune(): ?string
    {
        return $this->nom_ancienne_commune;
    }

    public function setNomAncienneCommune(?string $nom_ancienne_commune): self
    {
        $this->nom_ancienne_commune = $nom_ancienne_commune;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getNomLd(): ?string
    {
        return $this->nom_ld;
    }

    public function setNomLd(?string $nom_ld): self
    {
        $this->nom_ld = $nom_ld;

        return $this;
    }

    public function getLibelleAcheminement(): ?string
    {
        return $this->libelle_acheminement;
    }

    public function setLibelleAcheminement(string $libelle_acheminement): self
    {
        $this->libelle_acheminement = $libelle_acheminement;

        return $this;
    }

    public function getNomAfnor(): ?string
    {
        return $this->nom_afnor;
    }

    public function setNomAfnor(string $nom_afnor): self
    {
        $this->nom_afnor = $nom_afnor;

        return $this;
    }

    public function getSourcePosition(): ?string
    {
        return $this->source_position;
    }

    public function setSourcePosition(string $source_position): self
    {
        $this->source_position = $source_position;

        return $this;
    }

    public function getSourceNomVoie(): ?string
    {
        return $this->source_nom_voie;
    }

    public function setSourceNomVoie(string $source_nom_voie): self
    {
        $this->source_nom_voie = $source_nom_voie;

        return $this;
    }
}
