<?php

namespace App\Entity;

use DateTime;
use App\Entity\Role;
use App\Entity\Reply;
use DateTimeInterface;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Project;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      collectionOperations={},
 *      itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min=3,
     *      max=20,
     *      minMessage="You must enter at least {{ limit }} characters",
     *      maxMessage="You must enter a maximum of {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:comment"})
     * @Assert\Length(
     *      min=3,
     *      max=20,
     *      minMessage="You must enter at least {{ limit }} characters",
     *      maxMessage="You must enter a maximum of {{ limit }} characters"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255) 
     * @Groups({"read:comment"})
     * @Assert\Length(
     *      min=3,
     *      max=20,
     *      minMessage="You must enter at least {{ limit }} characters",
     *      maxMessage="You must enter a maximum of {{ limit }} characters"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:comment"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $roleUser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min=3,
     *      max=20,
     *      minMessage="You must enter at least {{ limit }} characters",
     *      maxMessage="You must enter at least {{ limit }} characters"
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read:comment"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="users", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="users")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="users")
     */
    private $projects;

    /**
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __construct()
    {
        $this->roleUser = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->replies = new ArrayCollection();
        $this->replyToReplies = new ArrayCollection();
    }

    public function __toString()
    {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
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
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roleUser->map(function ($role) {
            return $role->getName();
        })->toArray();

        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoleUser(): Collection
    {
        return $this->roleUser;
    }

    public function addRoleUser(Role $roleUser): self
    {
        if (!$this->roleUser->contains($roleUser)) {
            $this->roleUser[] = $roleUser;
            $roleUser->addUser($this);
        }

        return $this;
    }

    public function removeRoleUser(Role $roleUser): self
    {
        if ($this->roleUser->contains($roleUser)) {
            $this->roleUser->removeElement($roleUser);
            $roleUser->removeUser($this);
        }

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->firstname . ' ' . $this->lastname);
        }
    }

    public function updateSlug()
    {
        if (!empty($this->slug)) {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->firstname . ' ' . $this->lastname);
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUsers($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUsers() === $this) {
                $article->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUsers($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getUsers() === $this) {
                $project->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUsers($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUsers() === $this) {
                $comment->setUsers(null);
            }
        }

        return $this;
    }

    public function getDate()
    {
        $date = $this->getCreatedAt();

        $result = "{$date->format('\o\n l jS F Y')} at {$date->format('H:i')}";
        return $result;
    }
}
