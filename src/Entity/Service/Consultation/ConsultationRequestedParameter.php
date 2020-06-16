<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Consultation\ConsultationRequestedParameterRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsultationRequestedParameterRepository::class)
 * @ORM\Table(name="consultation_requested_parameters")
 * @ApiResource()
 */
class ConsultationRequestedParameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=ConsultationRequested::class, inversedBy="consultationRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La requête de consultation est requise")
     */
    private ?ConsultationRequested $consultationRequested = null;

    /**
     * @ORM\ManyToOne(targetEntity=HealthParameter::class, inversedBy="consultationRequestedDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le paramètre de santé est requis")
     */
    private ?HealthParameter $healthParameter = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La valeur du paramètre est attendue")
     */
    private ?string $value = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD- H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultationRequestedParameters")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsultationRequested(): ?ConsultationRequested
    {
        return $this->consultationRequested;
    }

    public function setConsultationRequested(?ConsultationRequested $consultationRequested): self
    {
        $this->consultationRequested = $consultationRequested;

        return $this;
    }

    public function getHealthParameter(): ?HealthParameter
    {
        return $this->healthParameter;
    }

    public function setHealthParameter(?HealthParameter $healthParameter): self
    {
        $this->healthParameter = $healthParameter;

        return $this;
    }

    public function getHealthParameterName(): ?string
    {
        return !is_null($this->healthParameter) ? $this->healthParameter->getParameterName() : null;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getParameterDetails(): ?array
    {
        if (is_null($this->healthParameter)) {
            return null;
        }
        return [
            'healthParameter' => $this->getHealthParameterName(),
            'value' => $this->getValue()
        ];
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
        } catch (Exception $exception) {
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
