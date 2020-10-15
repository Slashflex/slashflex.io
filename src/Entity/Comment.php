<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "order"={"createdAt":"DESC"},
 *      },
 *      paginationItemsPerPage=2,
 *      normalizationContext={"groups"={"read:comment"}},
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "controller"=App\Controller\Api\CommentCreateController::class
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"read:comment", "read:full:comment"}}
 *          },
 *          "delete"
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"article": "exact"})
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:comment"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:full:comment"})
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @Groups({"read:comment"})
     */
    private $users;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:comment"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="children")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     * @Groups({"read:comment"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="parent", orphanRemoval=true)
     * @Groups({"read:comment"})
     */
    private $children;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->children = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     * @return $this
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUsers(): ?User
    {
        return $this->users;
    }

    /**
     * @param User|null $users
     * @return $this
     */
    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new DateTime();
        }
    }

    /**
     * @return string
     */
    public function getDate()
    {
        $date = $this->getCreatedAt();

        $result = "{$date->format('\o\n l jS F Y')} at {$date->format('H:i')}";
        return $result;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return $this|null
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @param Comment|null $parent
     * @return $this
     */
    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Comment $child
     * @return $this
     */
    public function addParentChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    /**
     * @param Comment $child
     * @return $this
     */
    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}