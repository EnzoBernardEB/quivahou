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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="string", length=35)
     * @Assert\Regex(
     *     pattern="/\d{6,12}/",
     *     match=true,
     *     message="Votre numéro doit être composer entre 6 et 12 chiffres uniquement."
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCompleted = false;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="user", orphanRemoval=true)
     */
    private $experience;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="proprietaire")
     * @ORM\JoinColumn(nullable=true)
     */
    private $documents;


    /**
     * @ORM\OneToOne(targetEntity=PhotoProfil::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $filename;

    /**
     * @ORM\OneToOne(targetEntity=Adress::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=UserHasCompetence::class, mappedBy="user")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $userHasCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAvailable;

    /**
     * @ORM\OneToMany(targetEntity=RelationUser::class, mappedBy="user")
     */
    private $collegue;

    /**
     * @ORM\OneToMany(targetEntity=RelationUser::class, mappedBy="requestUser")
     */
    private $collegueRequest;

    /**
     * @ORM\Column(type="date")
     */
    private $anniversaryDate;

    /**
     * @ORM\Column(type="date")
     */
    private $modifDate;


    public function __construct()
    {
        $this->collegue_id = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->date_de_naissance= new \DateTime();
        $this->experience = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->userHasCompetences = new ArrayCollection();
        $this->collegue = new ArrayCollection();
        $this->collegueRequest = new ArrayCollection();
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


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experience->contains($experience)) {
            $this->experience[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experience->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setProprietaire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getProprietaire() === $this) {
                $document->setProprietaire(null);
            }
        }

        return $this;
    }

    public function getFilename(): ?PhotoProfil
    {
        return $this->filename;
    }

    public function setFilename(PhotoProfil $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAdresse(): ?Adress
    {
        return $this->adresse;
    }

    public function setAdresse(Adress $adresse): self
    {
        $this->adresse = $adresse;

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
            $userHasCompetence->setUser($this);
        }

        return $this;
    }

    public function removeUserHasCompetence(UserHasCompetence $userHasCompetence): self
    {
        if ($this->userHasCompetences->removeElement($userHasCompetence)) {
            // set the owning side to null (unless already changed)
            if ($userHasCompetence->getUser() === $this) {
                $userHasCompetence->setUser(null);
            }
        }

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

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * @return Collection|RelationUser[]
     */
    public function getCollegue(): Collection
    {
        return $this->collegue;
    }

    public function addCollegue(RelationUser $collegue): self
    {
        if (!$this->collegue->contains($collegue)) {
            $this->collegue[] = $collegue;
            $collegue->setUser($this);
        }

        return $this;
    }

    public function removeCollegue(RelationUser $collegue): self
    {
        if ($this->collegue->removeElement($collegue)) {
            // set the owning side to null (unless already changed)
            if ($collegue->getUser() === $this) {
                $collegue->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RelationUser[]
     */
    public function getCollegueRequest(): Collection
    {
        return $this->collegueRequest;
    }

    public function addCollegueRequest(RelationUser $collegueRequest): self
    {
        if (!$this->collegueRequest->contains($collegueRequest)) {
            $this->collegueRequest[] = $collegueRequest;
            $collegueRequest->setRequestUser($this);
        }

        return $this;
    }

    public function removeCollegueRequest(RelationUser $collegueRequest): self
    {
        if ($this->collegueRequest->removeElement($collegueRequest)) {
            // set the owning side to null (unless already changed)
            if ($collegueRequest->getRequestUser() === $this) {
                $collegueRequest->setRequestUser(null);
            }
        }

        return $this;
    }

    public function getAnniversaryDate(): ?\DateTimeInterface
    {
        return $this->anniversaryDate;
    }

    public function setAnniversaryDate(\DateTimeInterface $anniversaryDate): self
    {
        $this->anniversaryDate = $anniversaryDate;

        return $this;
    }

    public function getModifDate(): ?\DateTimeInterface
    {
        return $this->modifDate;
    }

    public function setModifDate(\DateTimeInterface $modifDate): self
    {
        $this->modifDate = $modifDate;

        return $this;
    }

}
