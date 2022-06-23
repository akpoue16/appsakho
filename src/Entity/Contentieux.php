<?php

namespace App\Entity;

use App\Repository\ContentieuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContentieuxRepository::class)
 */
class Contentieux
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
    private $code;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $objet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="contentieuxes")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Qualite::class, inversedBy="contentieuxes")
     */
    private $qualite;

    /**
     * @ORM\ManyToOne(targetEntity=Confrere::class, inversedBy="contentieuxes")
     */
    private $confrere;

    /**
     * @ORM\ManyToOne(targetEntity=Juridiction::class, inversedBy="contentieuxes")
     */
    private $juridiction;

    /**
     * @ORM\ManyToOne(targetEntity=Nature::class, inversedBy="contentieuxes")
     */
    private $nature;

    /**
     * @ORM\ManyToOne(targetEntity=Adversaire::class, inversedBy="contentieuxes")
     */
    private $adversaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getQualite(): ?Qualite
    {
        return $this->qualite;
    }

    public function setQualite(?Qualite $qualite): self
    {
        $this->qualite = $qualite;

        return $this;
    }

    public function getConfrere(): ?Confrere
    {
        return $this->confrere;
    }

    public function setConfrere(?Confrere $confrere): self
    {
        $this->confrere = $confrere;

        return $this;
    }

    public function getJuridiction(): ?Juridiction
    {
        return $this->juridiction;
    }

    public function setJuridiction(?Juridiction $juridiction): self
    {
        $this->juridiction = $juridiction;

        return $this;
    }

    public function getNature(): ?Nature
    {
        return $this->nature;
    }

    public function setNature(?Nature $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getAdversaire(): ?Adversaire
    {
        return $this->adversaire;
    }

    public function setAdversaire(?Adversaire $adversaire): self
    {
        $this->adversaire = $adversaire;

        return $this;
    }
}
