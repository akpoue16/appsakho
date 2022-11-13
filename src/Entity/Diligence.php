<?php

namespace App\Entity;

use App\Repository\DiligenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiligenceRepository::class)
 */
class Diligence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $debutTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $finTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity=Contentieux::class, inversedBy="diligences")
     */
    private $contentieux;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDebutTime(): ?\DateTimeInterface
    {
        return $this->debutTime;
    }

    public function setDebutTime(?\DateTimeInterface $debutTime): self
    {
        $this->debutTime = $debutTime;

        return $this;
    }

    public function getFinTime(): ?\DateTimeInterface
    {
        return $this->finTime;
    }

    public function setFinTime(?\DateTimeInterface $finTime): self
    {
        $this->finTime = $finTime;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getContentieux(): ?Contentieux
    {
        return $this->contentieux;
    }

    public function setContentieux(?Contentieux $contentieux): self
    {
        $this->contentieux = $contentieux;

        return $this;
    }
}
