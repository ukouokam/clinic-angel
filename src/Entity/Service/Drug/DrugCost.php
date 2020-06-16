<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Drug\DrugCostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugCostRepository::class)
 * @ORM\Table(name="drug_costs")
 * @ApiResource()
 */
class DrugCost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le coût ne doit pas rester vide")
     * @Assert\Type(type="float", message="Le coût doit numérique")
     * @Assert\Positive(message="La valeur doit être positive")
     */
    private ?float $unitPrice = null;

    /**
     * @ORM\ManyToOne(targetEntity=Drug::class, inversedBy="drugCosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Drug $drug = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drugCosts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur qui le crée est requis. Vous devez être connecté")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActual = true;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequestedDetail::class, mappedBy="drugCost")
     * @var DrugRequestedDetail[]|ArrayCollection
     */
    private $drugRequestedDetails;

    public function __construct()
    {
        $this->drugRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDrug(): ?Drug
    {
        return $this->drug;
    }

    public function setDrug(?Drug $drug): self
    {
        $this->drug = $drug;

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
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
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
            $drugRequestedDetail->setDrugCost($this);
        }

        return $this;
    }

    public function removeDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if ($this->drugRequestedDetails->contains($drugRequestedDetail)) {
            $this->drugRequestedDetails->removeElement($drugRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($drugRequestedDetail->getDrugCost() === $this) {
                $drugRequestedDetail->setDrugCost(null);
            }
        }

        return $this;
    }
}
