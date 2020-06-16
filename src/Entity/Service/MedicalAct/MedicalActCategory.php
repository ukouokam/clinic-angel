<?php

namespace App\Entity\Service\MedicalAct;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\MedicalAct\MedicalActCategoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MedicalActCategoryRepository::class)
 * @ORM\Table(name="medicals_acts_categories")
 * @ApiResource()
 */
class MedicalActCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé de la catégorie d'analyse ne peut pas être vide")
     * @Assert\Length(
     *     min="3", minMessage="Le libellé doit avoir au moins 03 caractères",
     *     max="100", maxMessage="Le libellé ne peut pas dépasser 100 caractères"
     * )
     */
    private ?string $wording = null;

    /**
     * @ORM\OneToMany(targetEntity=MedicalAct::class, mappedBy="category")
     * @var MedicalAct[]|ArrayCollection
     */
    private $medicalActs;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD- H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="medicalActCategories")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->medicalActs = new ArrayCollection();
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
     * @return Collection|MedicalAct[]
     */
    public function getMedicalActs(): Collection
    {
        return $this->medicalActs;
    }

    public function addMedicalAct(MedicalAct $medicalAct): self
    {
        if (!$this->medicalActs->contains($medicalAct)) {
            $this->medicalActs[] = $medicalAct;
            $medicalAct->setCategory($this);
        }

        return $this;
    }

    public function removeMedicalAct(MedicalAct $medicalAct): self
    {
        if ($this->medicalActs->contains($medicalAct)) {
            $this->medicalActs->removeElement($medicalAct);
            // set the owning side to null (unless already changed)
            if ($medicalAct->getCategory() === $this) {
                $medicalAct->setCategory(null);
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
}
