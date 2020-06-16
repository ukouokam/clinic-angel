<?php

namespace App\Entity\Service\MedicalAct;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\MedicalAct\MedicalActRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MedicalActRepository::class)
 * @ORM\Table(name="medicals_acts")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"medicals_acts_read"}
 *     }
 * )
 */
class MedicalAct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"medicals_acts_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({"medicals_acts_read"})
     * @Assert\NotBlank(message="Le libellé de l'acte médical doit être renseigné")
     * @Assert\Length(
     *     min="3", minMessage="Le libellé doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le libellé ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActCost::class, mappedBy="medicalAct")
     * @var MedicalActCost[]|ArrayCollection
     */
    private $medicalActCosts;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="medicalActs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"medicals_acts_read"})
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\ManyToOne(targetEntity=MedicalActCategory::class, inversedBy="medicalActs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La catégories d'analyse est manquante")
     */
    private ?MedicalActCategory $category = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"medicals_acts_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"medicals_acts_read"})
     * @Assert\Length(max="25", maxMessage="Le code ne doit pas dépasser 25 caractères")
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isBillable = true;

    /**
     * @ORM\OneToMany(targetEntity=MedicalActRequestedDetail::class, mappedBy="medicalAct")
     * @var MedicalActRequestedDetail[]|ArrayCollection
     */
    private $medicalActRequestedDetails;

    public function __construct()
    {
        $this->medicalActCosts = new ArrayCollection();
        $this->medicalActRequestedDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    /**
     * @return Collection|MedicalActCost[]
     */
    public function getMedicalActCosts(): Collection
    {
        return $this->medicalActCosts;
    }

    public function addMedicalActCost(MedicalActCost $medicalActCost): self
    {
        if (!$this->medicalActCosts->contains($medicalActCost)) {
            $this->medicalActCosts[] = $medicalActCost;
            $medicalActCost->setMedicalAct($this);
        }

        return $this;
    }

    public function removeMedicalActCost(MedicalActCost $medicalActCost): self
    {
        if ($this->medicalActCosts->contains($medicalActCost)) {
            $this->medicalActCosts->removeElement($medicalActCost);
            // set the owning side to null (unless already changed)
            if ($medicalActCost->getMedicalAct() === $this) {
                $medicalActCost->setMedicalAct(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     * @Groups("medicals_acts_read")
     */
    public function getActualUnitPrice(): ?float
    {
        if (is_null($this->medicalActCosts)) {
            return null;
        }
        /** @var MedicalActCost[] $costs */
        $costs = $this->medicalActCosts->toArray();
        $costs = array_filter($costs, static function($key) use ($costs) {
            return ($costs[$key]->getIsActual() === true);
        }, ARRAY_FILTER_USE_KEY);
        if (is_array($costs)) {
            if (count($costs) === 0) {
                return null;
            }
            return $costs[array_key_first($costs)]->getUnitPrice();
        }
        if ($costs instanceof MedicalActCost) {
            return $costs->getUnitPrice();
        }
        return  null;
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
     * @Groups({"medicals_acts_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    public function getCategory(): ?MedicalActCategory
    {
        return $this->category;
    }

    public function setCategory(?MedicalActCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"medicals_acts_read"})
     */
    public function getCategoryName(): ?string
    {
        return !is_null($this->category) ? $this->category->getWording() : null;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIsBillable(): ?bool
    {
        return $this->isBillable;
    }

    /**
     * @param bool|int|null $isBillable
     * @return $this
     */
    public function setIsBillable($isBillable): self
    {
        $this->isBillable = is_int($isBillable) ? (bool)$isBillable : $isBillable;

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
            $medicalActRequestedDetail->setMedicalAct($this);
        }

        return $this;
    }

    public function removeMedicalActRequestedDetail(MedicalActRequestedDetail $medicalActRequestedDetail): self
    {
        if ($this->medicalActRequestedDetails->contains($medicalActRequestedDetail)) {
            $this->medicalActRequestedDetails->removeElement($medicalActRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($medicalActRequestedDetail->getMedicalAct() === $this) {
                $medicalActRequestedDetail->setMedicalAct(null);
            }
        }

        return $this;
    }

}
