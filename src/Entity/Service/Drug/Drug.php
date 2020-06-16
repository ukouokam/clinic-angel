<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Drug\DrugRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugRepository::class)
 * @ORM\Table(name="drugs")
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"drugs_read"}
 *     }
 * )
 */
class Drug
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"drugs_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"drugs_read"})
     * @Assert\NotBlank(message="Le code du médicament est requis")
     * @Assert\Length(
     *     min="4", minMessage="Le code du médicament doit avoir au moins 04 caractères",
     *     max="100", maxMessage="Le code du médicament ne doit pas avoir plus de 100 caractères"
     * )
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"drugs_read"})
     * @Assert\NotBlank(message="Le nom du médicament est requis")
     * @Assert\Length(
     *     min="4", minMessage="Le nom du médicament doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le nom du médicament ne doit pas avoir plus de 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"drugs_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drugs")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\ManyToOne(targetEntity=DrugForm::class, inversedBy="drugs")
     * @Assert\NotBlank(message="La forme du médicament est manquante")
     */
    private ?DrugForm $drugForm = null;

    /**
     * @ORM\OneToMany(targetEntity=DrugCost::class, mappedBy="drug")
     * @var DrugCost[]|ArrayCollection
     */
    private $drugCosts;

    /**
     * @ORM\OneToMany(targetEntity=DrugRequestedDetail::class, mappedBy="drug")
     * @var DrugRequestedDetail[]|ArrayCollection
     */
    private $drugRequestedDetails;

    /**
     * @ORM\OneToMany(targetEntity=DrugPosology::class, mappedBy="drug")
     * @var DrugPosology[]|ArrayCollection
     */
    private $drugPosologies;



    public function __construct()
    {
        $this->drugCosts = new ArrayCollection();
        $this->drugRequestedDetails = new ArrayCollection();
        $this->drugPosologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    public function getCreateAt(): ?DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @param DateTimeInterface|string|null $createAt
     * @return $this
     */
    public function setCreateAt($createAt): self
    {
        try {
            $this->createAt = is_string($createAt) ? new DateTime($createAt) : $createAt;
        } catch (Exception $exception) {
            $this->createAt = null;
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
     * @Groups({"drugs_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }

    public function getDrugForm(): ?DrugForm
    {
        return $this->drugForm;
    }

    public function setDrugForm(?DrugForm $drugForm): self
    {
        $this->drugForm = $drugForm;

        return $this;
    }

    /**
     * @Groups({"drugs_read"})
     * @return string|null
     */
    public function getDrugPackaging(): ?string
    {
        return $this->drugForm->getWording();
    }

    /**
     * @return Collection|DrugCost[]
     */
    public function getDrugCosts(): Collection
    {
        return $this->drugCosts;
    }

    public function addDrugCost(DrugCost $drugCost): self
    {
        if (!$this->drugCosts->contains($drugCost)) {
            $this->drugCosts[] = $drugCost;
            $drugCost->setDrug($this);
        }

        return $this;
    }

    public function removeDrugCost(DrugCost $drugCost): self
    {
        if ($this->drugCosts->contains($drugCost)) {
            $this->drugCosts->removeElement($drugCost);
            // set the owning side to null (unless already changed)
            if ($drugCost->getDrug() === $this) {
                $drugCost->setDrug(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"drugs_read"})
     * @return float|null
     */
    public function getActualDrugCost(): ?float
    {
        /** @var DrugCost[] $costs */
        $costs = $this->drugCosts->toArray();
        $costs = array_filter($costs, static function($key) use ($costs) {
            return ($costs[$key]->getIsActual() === true);
        }, ARRAY_FILTER_USE_KEY);
        if ($costs instanceof DrugCost) {
            return $costs->getUnitPrice();
        }
        if (is_array($costs)) {
            $cost = $costs[array_key_first($costs)];
            return $cost->getUnitPrice();
        }
        return null;
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
            $drugRequestedDetail->setDrug($this);
        }

        return $this;
    }

    public function removeDrugRequestedDetail(DrugRequestedDetail $drugRequestedDetail): self
    {
        if ($this->drugRequestedDetails->contains($drugRequestedDetail)) {
            $this->drugRequestedDetails->removeElement($drugRequestedDetail);
            // set the owning side to null (unless already changed)
            if ($drugRequestedDetail->getDrug() === $this) {
                $drugRequestedDetail->setDrug(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DrugPosology[]
     */
    public function getDrugPosologies(): Collection
    {
        return $this->drugPosologies;
    }

    public function addDrugPosology(DrugPosology $drugPosology): self
    {
        if (!$this->drugPosologies->contains($drugPosology)) {
            $this->drugPosologies[] = $drugPosology;
            $drugPosology->setDrug($this);
        }

        return $this;
    }

    public function removeDrugPosology(DrugPosology $drugPosology): self
    {
        if ($this->drugPosologies->contains($drugPosology)) {
            $this->drugPosologies->removeElement($drugPosology);
            // set the owning side to null (unless already changed)
            if ($drugPosology->getDrug() === $this) {
                $drugPosology->setDrug(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"drugs_read"})
     * @return array|null
     */
    public function getDrugPosology(): ?array
    {
        if (is_null($this->drugPosologies)) {
            return null;
        }
        return array_map(static function(DrugPosology $posology) {
            return [
                'label' => $posology->getLabel(),
                'explanation' => $posology->getExplanation()
            ];
        }, $this->drugPosologies->toArray());
    }
}
