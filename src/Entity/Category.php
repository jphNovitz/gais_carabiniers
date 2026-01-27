<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 65, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: Types::STRING, length: 65, nullable: true)]
    private ?string $slugTitle = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 160, nullable: true)]
    private ?string $seoSummary = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['slugTitle'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childrenTopics')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?self $parentTopic = null;

    /** @var Collection<int,self> */
    #[ORM\OneToMany(mappedBy: 'parentTopic', targetEntity: self::class, cascade: ['persist'])]
    private Collection $childrenTopics;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->childrenTopics = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function ensureSlugBase(): void
    {
        if (!$this->slugTitle) {
            $this->slugTitle = $this->title;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle ?? $this->title;
    }

    public function setSeoTitle(?string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    public function getSlugTitle(): ?string
    {
        return $this->slugTitle ?? $this->title;
    }

    public function setSlugTitle(?string $slugTitle): void
    {
        $this->slugTitle = $slugTitle;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSeoSummary(): ?string
    {
        return $this->seoSummary ?? $this->summary;
    }

    public function setSeoSummary(?string $seoSummary): static
    {
        $this->seoSummary = $seoSummary;

        return $this;
    }

    public function getParentTopic(): ?self
    {
        return $this->parentTopic;
    }

    public function setParentTopic(?self $parentTopic): static
    {
        $this->parentTopic = $parentTopic;

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getChildrenTopics(): Collection
    {
        return $this->childrenTopics;
    }

    public function addChildrenTopic(Topic $childrenTopic): static
    {
        if (!$this->childrenTopics->contains($childrenTopic)) {
            $this->childrenTopics->add($childrenTopic);
            $childrenTopic->setParentTopic($this);
        }

        return $this;
    }

    public function removeChildrenTopic(Topic $childrenTopic): static
    {
        if ($this->childrenTopics->removeElement($childrenTopic)) {
            if ($childrenTopic->getParentTopic() === $this) {
                $childrenTopic->setParentTopic(null);
            }
        }

        return $this;
    }
}
