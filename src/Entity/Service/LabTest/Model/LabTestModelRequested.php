<?php

namespace App\Entity\Service\LabTest\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\Model\LabTestModelRequestedRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestModelRequestedRepository::class)
 * @ORM\Table(name="lab_test_models_requested")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"lab_test_models_requested_read"}
 *     }
 * )
 */
class LabTestModelRequested
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"lab_test_models_requested_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="labTestModelsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le patient est requis")
     */
    private ?Patient $patient = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestModelsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lab_test_models_requested_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"lab_test_models_requested_read"})
     */
    private ?string $comment = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequestedDetail::class, mappedBy="labTestModelRequested")
     * @var LabTestModelRequestedDetail[]|ArrayCollection
     */
    private $labTestModelRequestedDetails;

    /**
     * @ORM\ManyToOne(targetEntity=LabTestModelRate::class, inversedBy="labTestModelsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le pourcentage applicable est requis")
     */
    private ?LabTestModelRate $labTestModelRate = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUrgent = false;

    public function __construct()
    {
        $this->labTestModelRequestedDetails = new ArrayCollection();
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
     * @Groups({"lab_test_models_requested_read"})
     * @return string|null
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
     * @Groups({"lab_test_models_requested_read"})
     * @return string|null
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

    /**
     * @return Collection|LabTestModelRequestedDetail[]
     */
    public function getLabTestModelRequestedDetails(): Collection
    {
        return $this->labTestModelRequestedDetails;
    }

    public function addLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if (!$this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails[] = $labTestModelRequestedDetail;
            $labTestModelRequestedDetail->setLabTestModelRequested($this);
        }

        return $this;
    }

    public function removeLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if ($this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails->removeElement($labTestModelRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelRequestedDetail->getLabTestModelRequested() === $this) {
                $labTestModelRequestedDetail->setLabTestModelRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"lab_test_models_requested_read"})
     * @return array|null
     */
    public function getRequestDetails(): ?array
    {
        if ($this->labTestModelRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (LabTestModelRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->labTestModelRequestedDetails->toArray());
    }

    /**
     * @Groups({"lab_test_models_requested_read"})
     * @return float|null
     */
    public function getRequestCost(): ?float
    {
        if ($this->labTestModelRequestedDetails->count() === 0) {
            return null;
        }
        return array_reduce($this->labTestModelRequestedDetails->toArray(), static function(?float $initial, LabTestModelRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPrice();
            }
            return $requestedDetail->getUnitPrice() + $initial;
        }, null);
    }

    /**
     * @Groups({"lab_test_models_requested_read"})
     * @return float|null
     */
    public function getRequestCostWithRate(): ?float
    {
        if ($this->labTestModelRequestedDetails->count() ===0) {
            return null;
        }
        return array_reduce($this->labTestModelRequestedDetails->toArray(), static function(?float $initial, LabTestModelRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPriceWithRate();
            }
            return $requestedDetail->getUnitPriceWithRate() + $initial;
        }, null);
    }

    public function getLabTestModelRate(): ?LabTestModelRate
    {
        return $this->labTestModelRate;
    }

    public function setLabTestModelRate(?LabTestModelRate $labTestModelRate): self
    {
        $this->labTestModelRate = $labTestModelRate;

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
     * @Groups({"lab_test_models_requested_read"})
     * @return float|null
     */
    public function getRate(): ?float
    {
        return !is_null($this->labTestModelRate) ? $this->labTestModelRate->getRate() : null;
    }
}
