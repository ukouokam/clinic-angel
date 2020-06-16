<?php

namespace App\Entity\Service\MedicalAct;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use App\Repository\Service\MedicalAct\MedicalActRequestedRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MedicalActRequestedRepository::class)
 * @ORM\Table(name="medicals_acts_requested")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"medical_acts_requested_read"}
 *     }
 * )
 */
class MedicalActRequested
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"medical_acts_requested_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="medicalActRequesteds")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le patient est requis")
     */
    private ?Patient $patient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="medicalActRequesteds")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"medical_acts_requested_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment = null;

    /**
     * @Groups({"medical_acts_requested_read"})
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUrgent = false;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequestedDetail::class, mappedBy="medicalActRequested")
     * @var MedicalActRequestedDetail[]|ArrayCollection
     */
    private $medicalActRequestedDetails;

    public function __construct()
    {
        $this->medicalActRequestedDetails = new ArrayCollection();
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
     * @return string|null
     * @Groups({"medical_acts_requested_read"})
     */
    public function getPatientName(): ?string
    {
        return !is_null($this->patient) ? $this->patient->getFullName() : null;
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
     * @return string|null
     * @Groups({"medical_acts_requested_read"})
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
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

    /**
     * @return Collection|MedicalActRequestedDetail[]
     */
    public function getMedicalActRequestedDetails(): Collection
    {
        return $this->medicalActRequestedDetails;
    }

    public function addMedicalActRequestedDetail(MedicalActRequestedDetail $medicalActRequestedDetail): self
    {
        if (!$this->medicalActRequestedDetails->contains($medicalActRequestedDetail)) {
            $this->medicalActRequestedDetails[] = $medicalActRequestedDetail;
            $medicalActRequestedDetail->setMedicalActRequested($this);
        }

        return $this;
    }

    public function removeMedicalActRequestedDetail(MedicalActRequestedDetail $medicalActRequestedDetail): self
    {
        if ($this->medicalActRequestedDetails->contains($medicalActRequestedDetail)) {
            $this->medicalActRequestedDetails->removeElement($medicalActRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($medicalActRequestedDetail->getMedicalActRequested() === $this) {
                $medicalActRequestedDetail->setMedicalActRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"medical_acts_requested_read"})
     * @return array|null
     */
    public function getRequestDetails(): ?array
    {
        if ($this->medicalActRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (MedicalActRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestedDetail();
        }, $this->medicalActRequestedDetails->toArray());
    }

    /**
     * @Groups({"medical_acts_requested_read"})
     * @return float|null
     */
    public function getRequestCost(): ?float
    {
        if ($this->medicalActRequestedDetails->count() === 0) {
            return null;
        }
        return array_reduce($this->medicalActRequestedDetails->toArray(), static function (?float $initial, MedicalActRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPrice();
            }
            return $requestedDetail->getUnitPrice() + $initial;
        }, null);
    }
}
