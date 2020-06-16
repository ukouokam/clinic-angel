<?php

namespace App\Entity\Person\Staff\Technician;

use App\Entity\Person\User\User;
use App\Repository\Person\Staff\Technician\TechnicianCategoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechnicianCategoryRepository::class)
 * @ORM\Table(name="technicians_categories")
 */
class TechnicianCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=Technician::class, mappedBy="category")
     * @var Technician[]|ArrayCollection
     */
    private $technicians;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="technicianCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->technicians = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Technician[]
     */
    public function getTechnicians(): Collection
    {
        return $this->technicians;
    }

    public function addTechnician(Technician $technician): self
    {
        if (!$this->technicians->contains($technician)) {
            $this->technicians[] = $technician;
            $technician->setCategory($this);
        }

        return $this;
    }

    public function removeTechnician(Technician $technician): self
    {
        if ($this->technicians->contains($technician)) {
            $this->technicians->removeElement($technician);
            // set the owning side to null (unless already changed)
            if ($technician->getCategory() === $this) {
                $technician->setCategory(null);
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

    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
