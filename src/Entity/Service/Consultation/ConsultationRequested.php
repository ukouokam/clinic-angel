<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use App\Repository\Service\Consultation\ConsultationRequestedRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsultationRequestedRepository::class)
 * @ORM\Table(name="consultations_requested")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"consultations_requested_read"}
 *     }
 * )
 */
class ConsultationRequested
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"consultations_requested_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="consultationsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Sélectionner le patient qui sollicite la consultation")
     */
    private ?Patient $patient = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"consultations_requested_read"})
     * @Assert\NotBlank(message="La référence de la requête est obligatoire")
     */
    private ?string $reference = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"consultations_requested_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultationsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur qui crée la requête est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"consultations_requested_read"})
     */
    private ?string $comment = null;

    /**
     * @ORM\ManyToOne(targetEntity=ValidityConsultation::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La période de validité de requête est requise")
     */
    private ?ValidityConsultation $validityPeriod = null;

    /**
     * @todo mappedBy
     * @ORM\OneToMany(targetEntity=ConsultationRequestedDetail::class, mappedBy="consultationRequested")
     * @var ConsultationRequestedParameter[]|ArrayCollection
     */
    private $consultationRequestedParameters;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedDetail::class, mappedBy="consultationRequested")
     * @var ConsultationRequestedDetail[]|ArrayCollection
     */
    private $consultationRequestedDetails;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"consultations_requested_read"})
     */
    private ?bool $isUrgent = false;

    public function __construct()
    {
        $this->consultationRequestedParameters = new ArrayCollection();
        $this->consultationRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @Groups({"consultations_requested_read"})
     * @return string|null
     */
    public function getPatientName(): ?string
    {
        return !is_null($this->patient) ? $this->patient->getFullName() : null;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    /**
     * @Groups({"consultations_requested_read"})
     * @return string
     */
    public function getAuthor(): string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getValidityPeriod(): ?ValidityConsultation
    {
        return $this->validityPeriod;
    }

    public function setValidityPeriod(?ValidityConsultation $validityPeriod): self
    {
        $this->validityPeriod = $validityPeriod;

        return $this;
    }

    /**
     * @Groups({"consultations_requested_read"})
     * @return int|null
     */
    public function getValidityPeriodValue(): ?int
    {
        return !is_null($this->validityPeriod) ? $this->validityPeriod->getPeriodValidity() : null;
    }

    /**
     * @return Collection|ConsultationRequestedParameter[]
     */
    public function getConsultationRequestedParameters(): Collection
    {
        return $this->consultationRequestedParameters;
    }

    public function addConsultationRequestedParameter(ConsultationRequestedParameter $consultationRequestedParameter): self
    {
        if (!$this->consultationRequestedParameters->contains($consultationRequestedParameter)) {
            $this->consultationRequestedParameters[] = $consultationRequestedParameter;
            $consultationRequestedParameter->setConsultationRequested($this);
        }

        return $this;
    }

    public function removeConsultationRequestedParameter(ConsultationRequestedParameter $consultationRequestedParameter): self
    {
        if ($this->consultationRequestedParameters->contains($consultationRequestedParameter)) {
            $this->consultationRequestedParameters->removeElement($consultationRequestedParameter);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedParameter->getConsultationRequested() === $this) {
                $consultationRequestedParameter->setConsultationRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"consultations_requested_read"})
     * @return array|null
     */
    public function getParameterDetails(): ?array
    {
        if ($this->consultationRequestedParameters->count() === 0) {
            return null;
        }
        return array_map(static function (ConsultationRequestedParameter $requestedParameter) {
            return $requestedParameter->getParameterDetails();
        }, $this->consultationRequestedParameters->toArray());
    }

    /**
     * @return Collection|ConsultationRequestedDetail[]
     */
    public function getConsultationRequestedDetails(): Collection
    {
        return $this->consultationRequestedDetails;
    }

    public function addConsultationRequestedDetail(ConsultationRequestedDetail $consultationRequestedDetail): self
    {
        if (!$this->consultationRequestedDetails->contains($consultationRequestedDetail)) {
            $this->consultationRequestedDetails[] = $consultationRequestedDetail;
            $consultationRequestedDetail->setConsultationRequested($this);
        }

        return $this;
    }

    public function removeConsultationRequestedDetail(ConsultationRequestedDetail $consultationRequestedDetail): self
    {
        if ($this->consultationRequestedDetails->contains($consultationRequestedDetail)) {
            $this->consultationRequestedDetails->removeElement($consultationRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedDetail->getConsultationRequested() === $this) {
                $consultationRequestedDetail->setConsultationRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"consultations_requested_read"})
     * @return array|null
     */
    public function getConsultationDetails(): ?array
    {
        if ($this->consultationRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (ConsultationRequestedDetail $requestedDetail) {
            return $requestedDetail->getConsultationDetails();
        }, $this->consultationRequestedDetails->toArray());
    }

    public function getIsUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }
}
