<?php

namespace App\Entity;

use App\Repository\AudienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudienceRepository::class)
 */
class Audience
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
    private $code;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $conseil;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $motif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $procedures;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $renvoyer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomPresident;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomGreffier;

    /**
     * @ORM\ManyToOne(targetEntity=Contentieux::class, inversedBy="audiences")
     */
    private $contentieux;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getConseil(): ?string
    {
        return $this->conseil;
    }

    public function setConseil(?string $conseil): self
    {
        $this->conseil = $conseil;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getProcedures(): ?string
    {
        return $this->procedures;
    }

    public function setProcedures(?string $procedures): self
    {
        $this->procedures = $procedures;

        return $this;
    }

    public function getRenvoyer(): ?\DateTimeInterface
    {
        return $this->renvoyer;
    }

    public function setRenvoyer(?\DateTimeInterface $renvoyer): self
    {
        $this->renvoyer = $renvoyer;

        return $this;
    }

    public function getNomPresident(): ?string
    {
        return $this->nomPresident;
    }

    public function setNomPresident(?string $nomPresident): self
    {
        $this->nomPresident = $nomPresident;

        return $this;
    }

    public function getNomGreffier(): ?string
    {
        return $this->nomGreffier;
    }

    public function setNomGreffier(?string $nomGreffier): self
    {
        $this->nomGreffier = $nomGreffier;

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
