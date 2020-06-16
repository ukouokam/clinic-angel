<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Consultation\HealthParameterRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HealthParameterRepository::class)
 * @ORM\Table(name="health_parameters")
 * @ApiResource()
 */
class HealthParameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du paramètre de santé est requis")
     * @Assert\Length(
     *     min="3", minMessage="Le nom du paramètre de santé doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le nom du paramètre de santé doit avoir 100 caractères aux maximum"
     * )
     */
    private ?string $parameterName = null;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedParameter::class, mappedBy="healthParameter")
     * @var ConsultationRequestedParameter[]|ArrayCollection
     */
    private $consultationRequestedParameters;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="healthParameters")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->consultationRequestedParameters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParameterName(): ?string
    {
        return $this->parameterName;
    }

    public function setParameterName(string $parameterName): self
    {
        $this->parameterName = $parameterName;

        return $this;
    }

    /**
     * @return Collection|ConsultationRequestedParameter[]
     */
    public function getConsultationRequestedParameters(): Collection
    {
        return $this->consultationRequestedParameters;
    }

    public function addConsultationRequestedDetail(ConsultationRequestedParameter $consultationRequestedDetail): self
    {
        if (!$this->consultationRequestedParameters->contains($consultationRequestedDetail)) {
            $this->consultationRequestedParameters[] = $consultationRequestedDetail;
            $consultationRequestedDetail->setHealthParameter($this);
        }

        return $this;
    }

    public function removeConsultationRequestedDetail(ConsultationRequestedParameter $consultationRequestedDetail): self
    {
        if ($this->consultationRequestedParameters->contains($consultationRequestedDetail)) {
            $this->consultationRequestedParameters->removeElement($consultationRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedDetail->getHealthParameter() === $this) {
                $consultationRequestedDetail->setHealthParameter(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|string|null $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): self
    {
        try {
            $this->createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;
        } catch (\Exception $exception) {
            $this->createdAt = null;
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
