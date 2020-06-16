<?php

namespace App\Entity\Service\Drug;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\Drug\DrugFormRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DrugFormRepository::class)
 * @ORM\Table(name="drug_forms")
 * @ApiResource()
 */
class DrugForm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du médicament est requis")
     * @Assert\Length(
     *     min="4", minMessage="Le nom de l'emballage du médicament doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le nom de l'emballage médicament ne doit pas avoir plus de 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\OneToMany(targetEntity=Drug::class, mappedBy="drugForm")
     * @var Drug[]|ArrayCollection
     */
    private $drugs;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="drugForms")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->drugs = new ArrayCollection();
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
     * @return Collection|Drug[]
     */
    public function getDrugs(): Collection
    {
        return $this->drugs;
    }

    public function addDrug(Drug $drug): self
    {
        if (!$this->drugs->contains($drug)) {
            $this->drugs[] = $drug;
            $drug->setDrugForm($this);
        }

        return $this;
    }

    public function removeDrug(Drug $drug): self
    {
        if ($this->drugs->contains($drug)) {
            $this->drugs->removeElement($drug);
            // set the owning side to null (unless already changed)
            if ($drug->getDrugForm() === $this) {
                $drug->setDrugForm(null);
            }
        }

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
}
