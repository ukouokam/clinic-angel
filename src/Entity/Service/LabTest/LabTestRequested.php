<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\LabTestRequestedRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestRequestedRepository::class)
 * @ORM\Table(name="lab_tests_requested")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"lab_tests_requested_read"}
 *     }
 * )
 */
class LabTestRequested
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"lab_tests_requested_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="labTestsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La patient est requis")
     */
    private ?Patient $patient = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lab_tests_requested_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"lab_tests_requested_read"})
     */
    private ?string $comment = null;

    /**
     * @Groups({"lab_tests_requested_read"})
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUrgent = false;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequestedDetail::class, mappedBy="labTestRequested")
     * @var LabTestRequestedDetail[]|ArrayCollection
     */
    private $labTestRequestedDetails;

    public function __construct()
    {
        $this->labTestRequestedDetails = new ArrayCollection();
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
     * @Groups({"lab_tests_requested_read"})
     * @return string|null
     */
    public function getPatientName(): ?string
    {
        if (is_null($this->patient)) {
            return null;
        }
        return $this->patient->getFullName();
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
     * @Groups({"lab_tests_requested_read"})
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
     * @return Collection|LabTestRequestedDetail[]
     */
    public function getLabTestRequestedDetails(): Collection
    {
        return $this->labTestRequestedDetails;
    }

    public function addLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if (!$this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails[] = $labTestRequestedDetail;
            $labTestRequestedDetail->setLabTestRequested($this);
        }

        return $this;
    }

    public function removeLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if ($this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails->removeElement($labTestRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestRequestedDetail->getLabTestRequested() === $this) {
                $labTestRequestedDetail->setLabTestRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"lab_tests_requested_read"})
     * @return array|null
     */
    public function getRequestDetails(): ?array
    {
        if ($this->labTestRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (LabTestRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->labTestRequestedDetails->toArray());
    }

    /**
     * @Groups({"lab_tests_requested_read"})
     * @return float|null
     */
    public function getRequestCost(): ?float
    {
        if ($this->labTestRequestedDetails->count() === 0) {
            return null;
        }
        return array_reduce($this->labTestRequestedDetails->toArray(), static function (?float $initial, LabTestRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPrice();
            }
            return ($requestedDetail->getUnitPrice() + $initial);
        }, null);
    }
}
