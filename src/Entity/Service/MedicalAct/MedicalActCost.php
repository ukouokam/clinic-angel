<?php

namespace App\Entity\Service\MedicalAct;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\MedicalAct\MedicalActCostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MedicalActCostRepository::class)
 * @ORM\Table(
 *     name="medicals_acts_costs",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"medical_act_id", "is_actual", "created_at"})}
 * )
 * @ApiResource()
 */
class MedicalActCost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=MedicalAct::class, inversedBy="medicalActCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'acte médical est requis")
     */
    private ?MedicalAct $medicalAct = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le coût de l'acte médical ne peut pas être vide")
     * @Assert\Type(type="float", message="Le coût de l'acte médical doit être au format numérique")
     * @Assert\Positive(message="Le coût de l'acte médical doit être positif")
     */
    private ?float $unitPrice = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="medicalActCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActual = true;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequestedDetail::class, mappedBy="medicalActCost")
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

    public function getMedicalAct(): ?MedicalAct
    {
        return $this->medicalAct;
    }

    public function setMedicalAct(?MedicalAct $medicalAct): self
    {
        $this->medicalAct = $medicalAct;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

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

    public function getIsActual(): ?bool
    {
        return $this->isActual;
    }

    /**
     * @param bool|int|null $isActual
     * @return $this
     */
    public function setIsActual(bool $isActual): self
    {
        $this->isActual = is_int($isActual) ? (bool)$isActual : $isActual;

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
            $medicalActRequestedDetail->setMedicalActCost($this);
        }

        return $this;
    }

    public function removeMedicalActRequestedDetail(MedicalActRequestedDetail $medicalActRequestedDetail): self
    {
        if ($this->medicalActRequestedDetails->contains($medicalActRequestedDetail)) {
            $this->medicalActRequestedDetails->removeElement($medicalActRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($medicalActRequestedDetail->getMedicalActCost() === $this) {
                $medicalActRequestedDetail->setMedicalActCost(null);
            }
        }

        return $this;
    }
}
