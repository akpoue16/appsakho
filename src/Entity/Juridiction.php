<?php

namespace App\Entity;

use App\Repository\JuridictionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JuridictionRepository::class)
 */
class Juridiction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=Contentieux::class, mappedBy="juridiction")
     */
    private $contentieuxes;

    /**
     * @ORM\OneToMany(targetEntity=Audience::class, mappedBy="juridiction")
     */
    private $audiences;

    public function __construct()
    {
        $this->contentieuxes = new ArrayCollection();
        $this->audiences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, Contentieux>
     */
    public function getContentieuxes(): Collection
    {
        return $this->contentieuxes;
    }

    public function addContentieux(Contentieux $contentieux): self
    {
        if (!$this->contentieuxes->contains($contentieux)) {
            $this->contentieuxes[] = $contentieux;
            $contentieux->setJuridiction($this);
        }

        return $this;
    }

    public function removeContentieux(Contentieux $contentieux): self
    {
        if ($this->contentieuxes->removeElement($contentieux)) {
            // set the owning side to null (unless already changed)
            if ($contentieux->getJuridiction() === $this) {
                $contentieux->setJuridiction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Audience>
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): self
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences[] = $audience;
            $audience->setJuridiction($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): self
    {
        if ($this->audiences->removeElement($audience)) {
            // set the owning side to null (unless already changed)
            if ($audience->getJuridiction() === $this) {
                $audience->setJuridiction(null);
            }
        }

        return $this;
    }
}
