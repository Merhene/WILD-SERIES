<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
#[UniqueEntity(
    fields: ['title'],
    errorPath: 'title',
    message: 'cetitre existe déjà',
)]
#[Vich\Uploadable]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255,
    maxMessage: 'La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/plus belle la vie/',
        match: false,
        message: 'On parle de vraies séries ici',
    )]
    private ?string $synopsis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $posterFile = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Season::class)]
    private Collection $seasons;

    #[ORM\ManyToMany(targetEntity: Actor::class, mappedBy: 'programs')]
    private Collection $actors;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->actors = new ArrayCollection();
    }

    

      /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): void 
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setProgram($this);
        }
    }

    public function removeSeason(Season $season): void 
    {
        if ($this->seasons->removeElement($season)) {
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

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

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeProgram($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function setPosterFile(File $image = null): Program
    {
        $this->posterFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
          }
        return $this;
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
