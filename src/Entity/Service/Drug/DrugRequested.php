<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use App\Repository\Service\Drug\DrugRequestedRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugRequestedRepository::class)
 * @ORM\Table(name="drugs_requested")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"drugs_requested_read"}
 *     }
 * )
 */
class DrugRequested
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"drugs_requested_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="drugsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le patient est requis")
     */
    private ?Patient $patient = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"drugs_requested_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drugsRequested")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequestedDetail::class, mappedBy="drugRequested")
     * @var DrugRequestedDetail[]|ArrayCollection
     */
    private $drugRequestedDetails;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUrgent = false;

    public function __construct()
    {
        $this->drugRequestedDetails = new ArrayCollection();
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
     * @Groups({"drugs_requested_read"})
     * @return string|null
     */
    public function getPatientName(): ?string
    {
        if (is_null($this->patient)) {
            return null;
        }
        return $this->patient->getFullName();
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
     * @Groups({"drugs_requested_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    /**
     * @return Collection|DrugRequestedDetail[]
     */
    public function getDrugRequestedDetails(): Collection
    {
        return $this->drugRequestedDetails;
    }

    public function addDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if (!$this->drugRequestedDetails->contains($drugRequestedDetail)) {
            $this->drugRequestedDetails[] = $drugRequestedDetail;
            $drugRequestedDetail->setDrugRequested($this);
        }

        return $this;
    }

    public function removeDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if ($this->drugRequestedDetails->contains($drugRequestedDetail)) {
            $this->drugRequestedDetails->removeElement($drugRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($drugRequestedDetail->getDrugRequested() === $this) {
                $drugRequestedDetail->setDrugRequested(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"drugs_requested_read"})
     * @return array|null
     */
    public function getDrugPosologies(): ?array
    {
        if ($this->drugRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function(DrugRequestedDetail $requestedDetail) {
            return $requestedDetail->getDrugPosology();
        }, $this->drugRequestedDetails->toArray());
    }

    /**
     * @Groups({"drugs_requested_read"})
     * @return array|null
     */
    public function getRequestDetails(): ?array
    {
        if ($this->drugRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (DrugRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->drugRequestedDetails->toArray());
    }

    /**
     * @Groups({"drugs_requested_read"})
     * @return float|null
     */
    public function getTotalAmount(): ?float
    {
        return array_reduce($this->drugRequestedDetails->toArray(), static function(?float $initial, DrugRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getAmount();
            }
            return $requestedDetail->getAmount() + $initial;
        }, null);
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
