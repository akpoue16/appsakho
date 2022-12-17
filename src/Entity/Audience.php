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
    private $procedures;

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

    /**
     * @ORM\ManyToOne(targetEntity=Personnel::class, inversedBy="audiences")
     */
    private $avocat;

    /**
     * @ORM\ManyToOne(targetEntity=Juridiction::class, inversedBy="audiences")
     */
    private $juridiction;

    /**
     * @ORM\OneToOne(targetEntity=ResultatAudience::class, mappedBy="audience", cascade={"persist", "remove"})
     */
    private $resultatAudience;

    /**
     * @ORM\OneToOne(targetEntity=Audience::class, inversedBy="audience", cascade={"persist"})
     */
    private $audiencePre;

    /**
     * @ORM\OneToOne(targetEntity=Audience::class, mappedBy="audiencePre", cascade={"persist"})
     */
    private $audience;

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

    public function getProcedures(): ?string
    {
        return $this->procedures;
    }

    public function setProcedures(?string $procedures): self
    {
        $this->procedures = $procedures;

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

    public function getAvocat(): ?Personnel
    {
        return $this->avocat;
    }

    public function setAvocat(?Personnel $avocat): self
    {
        $this->avocat = $avocat;

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

    public function getResultatAudience(): ?ResultatAudience
    {
        return $this->resultatAudience;
    }

    public function setResultatAudience(?ResultatAudience $resultatAudience): self
    {
        // unset the owning side of the relation if necessary
        if ($resultatAudience === null && $this->resultatAudience !== null) {
            $this->resultatAudience->setAudience(null);
        }

        // set the owning side of the relation if necessary
        if ($resultatAudience !== null && $resultatAudience->getAudience() !== $this) {
            $resultatAudience->setAudience($this);
        }

        $this->resultatAudience = $resultatAudience;

        return $this;
    }

    public function getAudiencePre(): ?self
    {
        return $this->audiencePre;
    }

    public function setAudiencePre(?self $audiencePre): self
    {
        $this->audiencePre = $audiencePre;

        return $this;
    }

    public function getAudience(): ?self
    {
        return $this->audience;
    }

    public function setAudience(?self $audience): self
    {
        // unset the owning side of the relation if necessary
        if ($audience === null && $this->audience !== null) {
            $this->audience->setAudiencePre(null);
        }

        // set the owning side of the relation if necessary
        if ($audience !== null && $audience->getAudiencePre() !== $this) {
            $audience->setAudiencePre($this);
        }

        $this->audience = $audience;

        return $this;
    }
}
