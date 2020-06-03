<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $content_1;

    /**
     * @ORM\Column(type="text")
     */
    private $content_2;

    /**
     * @ORM\Column(type="text")
     */
    private $content_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $main_image;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="articles", orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Field::class, inversedBy="articles", orphanRemoval=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     */
    private $users;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent1(): ?string
    {
        return $this->content_1;
    }

    public function setContent1(string $content_1): self
    {
        $this->content_1 = $content_1;

        return $this;
    }

    public function getContent2(): ?string
    {
        return $this->content_2;
    }

    public function setContent2(string $content_2): self
    {
        $this->content_2 = $content_2;

        return $this;
    }

    public function getContent3(): ?string
    {
        return $this->content_3;
    }

    public function setContent3(string $content_3): self
    {
        $this->content_3 = $content_3;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->main_image;
    }

    public function setMainImage(string $main_image): self
    {
        $this->main_image = $main_image;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
        }

        return $this;
    }

    /**
     * @return Collection|Field[]
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(Field $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content[] = $content;
        }

        return $this;
    }

    public function removeContent(Field $content): self
    {
        if ($this->content->contains($content)) {
            $this->content->removeElement($content);
        }

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @Orm\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->title);
        }
    }
    public function updateSlug()
    {
        if (!empty($this->slug)) {
            $slug = new Slugify();
            $this->slug = $slug->slugify($this->title);
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }
}
