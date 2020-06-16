<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Consultation\ConsultationCategoryCostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsultationCategoryCostRepository::class)
 * @ORM\Table(name="consultation_category_costs")
 * @ApiResource()
 */
class ConsultationCategoryCost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=ConsultationCategory::class, inversedBy="consultationCategoryCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La catégorie est requise pour créer le coût")
     */
    private ?ConsultationCategory $consultationCategory = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultationCategoryCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Vous devez être connecté pour effectuer cette opération")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message="Le coût doit être numérique")
     */
    private ?float $unitPrice = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActual = true;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedDetail::class, mappedBy="consultationCategoryCost")
     * @var ConsultationRequestedDetail[]|ArrayCollection
     */
    private $consultationRequestedDetails;

    public function __construct()
    {
        $this->consultationRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsultationCategory(): ?ConsultationCategory
    {
        return $this->consultationCategory;
    }

    public function setConsultationCategory(?ConsultationCategory $consultationCategory): self
    {
        $this->consultationCategory = $consultationCategory;

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

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getIsActual(): ?bool
    {
        return $this->isActual;
    }

    public function setIsActual(bool $isActual): self
    {
        $this->isActual = $isActual;

        return $this;
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
            $consultationRequestedDetail->setConsultationCategoryCost($this);
        }

        return $this;
    }

    public function removeConsultationRequestedDetail(ConsultationRequestedDetail $consultationRequestedDetail): self
    {
        if ($this->consultationRequestedDetails->contains($consultationRequestedDetail)) {
            $this->consultationRequestedDetails->removeElement($consultationRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedDetail->getConsultationCategoryCost() === $this) {
                $consultationRequestedDetail->setConsultationCategoryCost(null);
            }
        }

        return $this;
    }
}
