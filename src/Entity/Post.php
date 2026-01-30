<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Table(name: 'posts')]
#[ORM\HasLifecycleCallbacks]
class Post
{
    use TimestampableEntity;

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

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $pin = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $published = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 160, nullable: true)]
    private ?string $seoSummary = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['slugTitle'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

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

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle ?? $this->title;
    }

    public function setSeoTitle(?string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSlugTitle(): ?string
    {
        return $this->slugTitle ?? $this->title;
    }

    public function setSlugTitle(?string $slugTitle): self
    {
        $this->slugTitle = $slugTitle;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isPin(): bool
    {
        return $this->pin;
    }

    public function setPin(bool $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSeoSummary(): ?string
    {
        return $this->seoSummary ?? $this->summary;
    }

    public function setSeoSummary(?string $seoSummary): self
    {
        $this->seoSummary = $seoSummary;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function ensureSlugBase(): void
    {
        if (!$this->slugTitle) {
            $this->slugTitle = $this->title;
        }
    }

    public function getFirstParagraph(int $count = 2): string|array
    {

        // Récupère TOUS les <p>
        preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $this->content, $matches);

        // $matches[1] contient tous les contenus des <p>
        // On prend les N premiers
        $paragraphs = array_slice($matches[1], 0, $count);

        // Nettoie chaque paragraphe (enlève les balises internes si besoin)
        return array_map('strip_tags', $paragraphs);
    }
    public function getExcerpt(int $length = 150): string
    {
        // Convertit <p>, <br> en espaces
        $text = str_replace(['</p>', '<br>', '<br/>'], ' ', $this->content);
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, mb_strrpos(mb_substr($text, 0, $length), ' ')) . '...';
    }
}
