<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @UniqueEntity(fields="name", message="Une figure portant ce nom existe déjà", groups={"new"})
 * @CustomAssert\TrickName(groups={"edit"})
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner un nom de figure.", groups={"new","edit"})
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private ?Group $group;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * @ORM\OneToMany(targetEntity=TrickVideo::class, mappedBy="trick", orphanRemoval=true, cascade={"persist","remove"})
     * @Assert\Valid(groups={"new","edit"})
     */
    private Collection $trickVideos;

    /**
     * @ORM\OneToMany(targetEntity=TrickImage::class, mappedBy="trick", orphanRemoval=true, cascade={"persist","remove"})
     * @Assert\Valid(groups={"new","edit"})
     */
    private Collection $trickImages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $mainImage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    private ?UploadedFile $mainImageFile = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->trickVideos = new ArrayCollection();
        $this->trickImages = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getMainImageFile()
    {
        return $this->mainImageFile;
    }

    /**
     * @param UploadedFile $mainImageFile
     */
    public function setMainImageFile(UploadedFile $mainImageFile)
    {
        $this->mainImageFile = $mainImageFile;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getgroup(): ?Group
    {
        return $this->group;
    }

    public function setgroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getuser(): ?User
    {
        return $this->user;
    }

    public function setuser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $comment->settrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->gettrick() === $this) {
                $comment->settrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrickVideo[]
     */
    public function getTrickVideos(): Collection
    {
        return $this->trickVideos;
    }

    public function addTrickVideo(TrickVideo $trickVideo): self
    {
        if (!$this->trickVideos->contains($trickVideo)) {
            $trickVideo->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));
            $this->trickVideos[] = $trickVideo;
            $trickVideo->settrick($this);
        }

        return $this;
    }

    public function getTrickVideo($id): TrickVideo
    {
        foreach ($this->trickVideos as $trickVideo) {
            if ($trickVideo->getId() == $id) return $trickVideo;
        }
        return new TrickVideo();
    }

    public function removeTrickVideo(TrickVideo $trickVideo): self
    {
        if ($this->trickVideos->removeElement($trickVideo)) {
            // set the owning side to null (unless already changed)
            if ($trickVideo->gettrick() === $this) {
                $trickVideo->settrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrickImage[]
     */
    public function getTrickImages(): Collection
    {
        return $this->trickImages;
    }

    public function getTrickImage($id): TrickImage
    {
        foreach ($this->trickImages as $trickImage) {
            if ($trickImage->getId() == $id) return $trickImage;
        }
        return new TrickImage();
    }

    public function addTrickImage(TrickImage $trickImage): self
    {

        $trickImage->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));
        $this->trickImages[] = $trickImage;
        $trickImage->setTrick($this);

        return $this;
    }

    public function removeTrickImage(TrickImage $trickImage): self
    {
        if ($this->trickImages->removeElement($trickImage)) {
            // set the owning side to null (unless already changed)
            if ($trickImage->getTrick() === $this) {
                $trickImage->setTrick(null);
            }
        }

        return $this;
    }


    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function deleteMainImage()
    {
        unlink('images/tricks/' . $this->mainImage);

        $this->mainImage = null;

        return $this;
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
}
