<?php

namespace App\Entity\Service\Consultation;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Consultation\ConsultationCategoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsultationCategoryRepository::class)
 * @ORM\Table(name="consultation_categories")
 * @ApiResource(
 *     normalizationContext={
 *     "groups"={"consultation_categories_read"}
 *     }
 * )
 */
class ConsultationCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"consultation_categories_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la catégorie est requis")
     * @Assert\Length(
     *     min="3", minMessage="Le nom de la catégorie doit avoir 03 caractères au minimum",
     *     max="100", maxMessage="Le nom de la catégorie ne peut pas dépasser 100 caractères"
     * )
     * @Groups({"consultation_categories_read"})
     */
    private ?string $wording = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     * @Groups({"consultation_categories_read"})
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consultationCategories")
     * @Assert\NotBlank(message="Vous devez être connecté pour réaliser cette opération")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationCategoryCost::class, mappedBy="consultationCategory")
     * @var ConsultationCategoryCost[]|ArrayCollection
     */
    private $consultationCategoryCosts;

    /**
     * @ORM\OneToMany(targetEntity=ConsultationRequestedDetail::class, mappedBy="consultationCategory")
     * @var ConsultationRequestedDetail[]|ArrayCollection
     */
    private $consultationRequestedDetails;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"consultation_categories_read"})
     * @Assert\NotBlank(message="Le code de la catégorie est requis")
     */
    private ?string $code = null;

    public function __construct()
    {
        $this->consultationCategoryCosts = new ArrayCollection();
        $this->consultationRequestedDetails = new ArrayCollection();
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
     * @Groups({"consultation_categories_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    /**
     * @return Collection|ConsultationCategoryCost[]
     */
    public function getConsultationCategoryCosts(): Collection
    {
        return $this->consultationCategoryCosts;
    }

    public function addConsultationCategoryCost(ConsultationCategoryCost $consultationCategoryCost): self
    {
        if (!$this->consultationCategoryCosts->contains($consultationCategoryCost)) {
            $this->consultationCategoryCosts[] = $consultationCategoryCost;
            $consultationCategoryCost->setConsultationCategory($this);
        }

        return $this;
    }

    public function removeConsultationCategoryCost(ConsultationCategoryCost $consultationCategoryCost): self
    {
        if ($this->consultationCategoryCosts->contains($consultationCategoryCost)) {
            $this->consultationCategoryCosts->removeElement($consultationCategoryCost);
            // set the owning side to null (unless already changed)
            if ($consultationCategoryCost->getConsultationCategory() === $this) {
                $consultationCategoryCost->setConsultationCategory(null);
            }
        }

        return $this;
    }

    /**
     * {"consultation_categories_read"}
     * @return float|null
     */
    public function getActualCost(): ?float
    {
        /** @var ConsultationCategoryCost[] $costs */
        $costs = $this->consultationCategoryCosts->toArray();

        if (count($costs) === 0) {
            return null;
        }

        $costs = array_filter($costs, static function ($key) use ($costs) {
            return ($costs[$key]->getIsActual() === true);
        }, ARRAY_FILTER_USE_KEY);
        if (is_array($costs)) {
            if (count($costs) === 0) {
                return null;
            }
            return $costs[array_key_first($costs)]->getUnitPrice();
        }
        if ($costs instanceof ConsultationCategoryCost) {
            return $costs->getUnitPrice();
        }
        return null;
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
            $consultationRequestedDetail->setConsultationCategory($this);
        }

        return $this;
    }

    public function removeConsultationRequestedDetail(ConsultationRequestedDetail $consultationRequestedDetail): self
    {
        if ($this->consultationRequestedDetails->contains($consultationRequestedDetail)) {
            $this->consultationRequestedDetails->removeElement($consultationRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($consultationRequestedDetail->getConsultationCategory() === $this) {
                $consultationRequestedDetail->setConsultationCategory(null);
            }
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
}
