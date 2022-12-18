<?php

namespace App\Entity;

use App\Repository\ResultatAudienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultatAudienceRepository::class)
 */
class ResultatAudience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resultat;

    /**
     * @ORM\OneToOne(targetEntity=Audience::class, inversedBy="resultatAudience")
     */
    private $audience;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(?string $resultat): self
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getAudience(): ?Audience
    {
        return $this->audience;
    }

    public function setAudience(?Audience $audience): self
    {
        $this->audience = $audience;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getResultat();  // or some string field in your Vegetal Entity 
    }
}
