<?php

namespace App\Entity;

use App\Repository\QualiteAdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QualiteAdRepository::class)
 */
class QualiteAd
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
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=Contentieux::class, mappedBy="qualiteAd")
     */
    private $contentieux;

    public function __construct()
    {
        $this->contentieux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, Contentieux>
     */
    public function getContentieux(): Collection
    {
        return $this->contentieux;
    }

    public function addContentieux(Contentieux $contentieux): self
    {
        if (!$this->contentieux->contains($contentieux)) {
            $this->contentieux[] = $contentieux;
            $contentieux->setQualiteAd($this);
        }

        return $this;
    }

    public function removeContentieux(Contentieux $contentieux): self
    {
        if ($this->contentieux->removeElement($contentieux)) {
            // set the owning side to null (unless already changed)
            if ($contentieux->getQualiteAd() === $this) {
                $contentieux->setQualiteAd(null);
            }
        }

        return $this;
    }
}
