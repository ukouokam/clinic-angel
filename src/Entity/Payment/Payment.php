<?php

namespace App\Entity\Payment;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Entity\Service\Consultation\ConsultationRequestedDetail;
use App\Entity\Service\Drug\DrugRequestedDetail;
use App\Entity\Service\LabTest\LabTestRequestedDetail;
use App\Entity\Service\LabTest\Model\LabTestModelRequestedDetail;
use App\Entity\Service\MedicalAct\MedicalActRequestedDetail;
use App\Repository\Payment\PaymentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 * @ORM\Table(name="payments")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"payments_read"}
 *     }
 * )
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"payments_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"payments_read"})
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"payments_read"})
     */
    private ?string $reference = null;

    /**
     * @ORM\OneToMany(targetEntity=AdvancePayment::class, mappedBy="payment")
     * @var AdvancePayment[]|ArrayCollection
     */
    private $advancePayments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment = null;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequestedDetail::class, mappedBy="payment")
     * @var DrugRequestedDetail[]|ArrayCollection
     */
    private $drugRequestedDetail;

    /**
     * @ORM\OneToMany(targetEntity=LabTestRequestedDetail::class, mappedBy="payment")
     * @var LabTestRequestedDetail[]|ArrayCollection
     */
    private $labTestRequestedDetails;

    /**
     * @ORM\OneToMany(targetEntity=LabTestModelRequestedDetail::class, mappedBy="payment")
     * @var LabTestModelRequestedDetail[]|ArrayCollection
     */
    private $labTestModelRequestedDetails;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequestedDetail::class, mappedBy="payment")
     * @var MedicalActRequestedDetail[]|ArrayCollection
     */
    private $medicalActRequestedDetails;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedDetail::class, mappedBy="payment")
     * @var ConsultationRequestedDetail[]|ArrayCollection
     */
    private $consultationRequestedDetails;

    public function __construct()
    {
        $this->advancePayments = new ArrayCollection();
        $this->drugRequestedDetail = new ArrayCollection();
        $this->labTestRequestedDetails = new ArrayCollection();
        $this->labTestModelRequestedDetails = new ArrayCollection();
        $this->medicalActRequestedDetails = new ArrayCollection();
        $this->consultationRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     * @Groups({"payments_read"})
     */
    public function getPatientName(): ?string
    {
        return '';
    }

    /**
     * @return string|null
     * @Groups({"payments_read"})
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Permet d'obtenir le coût total des service à la charge du patient
     * @return float|null
     * @Groups({"payments_read"})
     */
    public function getTotalPayment(): ?float
    {
        if (
            $this->labTestRequestedDetails->count() === 0 &&
            $this->labTestModelRequestedDetails->count() === 0 &&
            $this->consultationRequestedDetails->count() === 0 &&
            $this->drugRequestedDetail->count() === 0 &&
            $this->medicalActRequestedDetails->count() === 0
        ) {
            return null;
        }
        return
            $this->getLabTestPayment() +
            $this->getLabTestModelPayment() +
            $this->getConsultationPayment() +
            $this->getDrugPayment() +
            $this->getMedicalActPayment();
    }

    /**
     * Permet d'obtenir les acomptes effectués par le patient
     * @return float|null
     * @Groups({"payments_read"})
     */
    public function getAdvancePayment(): ?float
    {
        if ($this->advancePayments->count() === 0) {
            return null;
        }
        return array_reduce($this->advancePayments->toArray(), static function(?float $initial, AdvancePayment $advancePayment) {
            if (is_null($initial)) {
                return $advancePayment->getAmount();
            }
            return $advancePayment->getAmount() + $initial;
        }, null);
    }

    /**
     * Permet d'obtenir le montant restant des paiements à la charge du patient
     * @return float|null
     * @Groups({"payments_read"})
     */
    public function getRestPayment(): ?float
    {
        return
            $this->getTotalPayment() -
            $this->getAdvancePayment();
    }

    /**
     * @return Collection|AdvancePayment[]
     */
    public function getAdvancePayments(): Collection
    {
        return $this->advancePayments;
    }

    public function addAdvancePayment(AdvancePayment $advancePayment): self
    {
        if (!$this->advancePayments->contains($advancePayment)) {
            $this->advancePayments[] = $advancePayment;
            $advancePayment->setPayment($this);
        }

        return $this;
    }

    public function removeAdvancePayment(AdvancePayment $advancePayment): self
    {
        if ($this->advancePayments->contains($advancePayment)) {
            $this->advancePayments->removeElement($advancePayment);
            // set the owning side to null (unless already changed)
            if ($advancePayment->getPayment() === $this) {
                $advancePayment->setPayment(null);
            }
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
     * @return Collection|DrugRequestedDetail[]
     */
    public function getDrugRequestedDetail(): Collection
    {
        return $this->drugRequestedDetail;
    }

    public function addDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if (!$this->drugRequestedDetail->contains($drugRequestedDetail)) {
            $this->drugRequestedDetail[] = $drugRequestedDetail;
            $drugRequestedDetail->setPayment($this);
        }

        return $this;
    }

    public function removeDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if ($this->drugRequestedDetail->contains($drugRequestedDetail)) {
            $this->drugRequestedDetail->removeElement($drugRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($drugRequestedDetail->getPayment() === $this) {
                $drugRequestedDetail->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * Permet d'obtenir le total des coûts des médicaments à payer par le patient
     * @return float|null
     * @Groups({"payments_read"})
     */
    public function getDrugPayment(): ?float
    {
        if (is_null($this->drugRequestedDetail)) {
            return null;
        }
        return array_reduce($this->drugRequestedDetail->toArray(), static function(?float $initial, DrugRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getAmount();
            }
            return $requestedDetail->getAmount() + $initial;
        }, null);
    }

    /**
     * @Groups({"payments_read"})
     * @return array|null
     */
    public function getDrugDetails(): ?array
    {
        if ($this->drugRequestedDetail->count() === 0) {
            return null;
        }
        return array_map(static function (DrugRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->drugRequestedDetail->toArray());
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
            $labTestRequestedDetail->setPayment($this);
        }

        return $this;
    }

    public function removeLabTestRequestedDetail(LabTestRequestedDetail $labTestRequestedDetail): self
    {
        if ($this->labTestRequestedDetails->contains($labTestRequestedDetail)) {
            $this->labTestRequestedDetails->removeElement($labTestRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestRequestedDetail->getPayment() === $this) {
                $labTestRequestedDetail->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"payments_read"})
     * @return float|null
     */
    public function getLabTestPayment(): ?float
    {
        if (is_null($this->labTestRequestedDetails)) {
            return null;
        }
        return array_reduce($this->labTestRequestedDetails->toArray(), static function (?float $initial, LabTestRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPrice();
            }
            return $requestedDetail->getUnitPrice() + $initial;
        }, null);
    }

    /**
     * @Groups({"payments_read"})
     * @return array|null
     */
    public function getLabTestDetails(): ?array
    {
        if ($this->labTestRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (LabTestRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->labTestRequestedDetails->toArray());
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
            $labTestModelRequestedDetail->setPayment($this);
        }

        return $this;
    }

    public function removeLabTestModelRequestedDetail(LabTestModelRequestedDetail $labTestModelRequestedDetail): self
    {
        if ($this->labTestModelRequestedDetails->contains($labTestModelRequestedDetail)) {
            $this->labTestModelRequestedDetails->removeElement($labTestModelRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($labTestModelRequestedDetail->getPayment() === $this) {
                $labTestModelRequestedDetail->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"payments_read"})
     * @return float|null
     */
    public function getLabTestModelPayment(): ?float
    {
        if ($this->labTestModelRequestedDetails->count() === 0) {
            return null;
        }
        return array_reduce($this->labTestModelRequestedDetails->toArray(), static function(?float $initial, LabTestModelRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPriceWithRate();
            }
            return $requestedDetail->getUnitPriceWithRate() + $initial;
        }, null);
    }

    /**
     * Le tableau retourné est groupé par le nom du modèle qui est la clé de chaque occurrence
     * @Groups({"payments_read"})
     * @return array|null
     */
    public function getLabTestModelDetails(): ?array
    {
        if ($this->labTestModelRequestedDetails->count() === 0) {
            return null;
        }
        $details = array_map(static function(LabTestModelRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestDetail();
        }, $this->labTestModelRequestedDetails->toArray());
        return $this->groupBy('labTestModelName', $details);
    }

    /**
     * Permet d'effectuer le regroupement d'un tableau associatif à travers une clé donnée
     * @param $key
     * @param array $data
     * @return array
     */
    private function groupBy($key, array $data): array
    {
        $result = [];
        foreach ($data as $datum) {
            if (array_key_exists($key, $datum)) {
                //On garde la valeur qui permet de regrouper
                $getKey = $datum[$key];
                //On supprime la valeur du regroupement dans le tableau des valeurs
                unset($datum[array_search($key, $datum, true)]);
                $result[$getKey] = $datum;
            }
        }
        return $result;
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
            $medicalActRequestedDetail->setPayment($this);
        }

        return $this;
    }

    public function removeMedicalActRequestedDetail(MedicalActRequestedDetail $medicalActRequestedDetail): self
    {
        if ($this->medicalActRequestedDetails->contains($medicalActRequestedDetail)) {
            $this->medicalActRequestedDetails->removeElement($medicalActRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($medicalActRequestedDetail->getPayment() === $this) {
                $medicalActRequestedDetail->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"payments_read"})
     * @return float|null
     */
    public function getMedicalActPayment(): ?float
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

    /**
     * @Groups({"payments_read"})
     * @return array|null
     */
    public function getMedicalActDetails(): ?array
    {
        if ($this->medicalActRequestedDetails->count() === 0) {
            return null;
        }
        return array_map(static function (MedicalActRequestedDetail $requestedDetail) {
            return $requestedDetail->getRequestedDetail();
        }, $this->medicalActRequestedDetails->toArray());
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
            $consultationRequestedDetail->setPayment($this);
        }

        return $this;
    }

    public function removeConsultationRequestedDetail(ConsultationRequestedDetail $consultationRequestedDetail): self
    {
        if ($this->consultationRequestedDetails->contains($consultationRequestedDetail)) {
            $this->consultationRequestedDetails->removeElement($consultationRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedDetail->getPayment() === $this) {
                $consultationRequestedDetail->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"payments_read"})
     * @return float|null
     */
    public function getConsultationPayment(): ?float
    {
        if ($this->consultationRequestedDetails->count() === 0) {
            return null;
        }
        return array_reduce($this->consultationRequestedDetails->toArray(), static function (?float $initial, ConsultationRequestedDetail $requestedDetail) {
            if (is_null($initial)) {
                return $requestedDetail->getUnitPrice();
            }
            return $requestedDetail->getUnitPrice() + $initial;
        }, null);
    }

    /**
     * @Groups({"payments_read"})
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
}
