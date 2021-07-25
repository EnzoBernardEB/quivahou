<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Une erreur est présente dans vos informations, vérifier tous les champs.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $date_de_naissance;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="users")
     */
    private $collegue_id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="collegue_id")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="users")
     */
    private $competence_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $intitule_document;

    /**
     * @ORM\Column(type="string", length=35)
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="Votre numéro ne doit contenir que des chiffres."
     * )
     */
    private $telephone;

    public function __construct()
    {
        $this->collegue_id = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->competence_id = new ArrayCollection();
        $this->date_de_naissance= new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCollegueId(): Collection
    {
        return $this->collegue_id;
    }

    public function addCollegueId(self $collegueId): self
    {
        if (!$this->collegue_id->contains($collegueId)) {
            $this->collegue_id[] = $collegueId;
        }

        return $this;
    }

    public function removeCollegueId(self $collegueId): self
    {
        $this->collegue_id->removeElement($collegueId);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCollegueId($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeCollegueId($this);
        }

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetenceId(): Collection
    {
        return $this->competence_id;
    }

    public function addCompetenceId(Competence $competenceId): self
    {
        if (!$this->competence_id->contains($competenceId)) {
            $this->competence_id[] = $competenceId;
        }

        return $this;
    }

    public function removeCompetenceId(Competence $competenceId): self
    {
        $this->competence_id->removeElement($competenceId);

        return $this;
    }

    public function getIntituleDocument(): ?string
    {
        return $this->intitule_document;
    }

    public function setIntituleDocument(?string $intitule_document): self
    {
        $this->intitule_document = $intitule_document;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
