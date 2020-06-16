<?php

namespace App\Entity\Person\Staff\OtherStaff;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Person\Staff\OtherStaff\OtherTypeStaffRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OtherTypeStaffRepository::class)
 * @ORM\Table(name="others_types_staff")
 * @ApiResource()
 */
class OtherTypeStaff
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est requis")
     * @Assert\Length(
     *     min="3", minMessage="Le nom doit avoir 03 caractères au minimum",
     *     max="50", maxMessage="Le nom ne peut pas dépasser 50 caractères"
     * )
     */
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=OtherStaff::class, mappedBy="typeStaff")
     * @var OtherStaff[]|ArrayCollection
     */
    private $otherStaff;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="otherTypeStaff")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->otherStaff = new ArrayCollection();
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
     * @return Collection|OtherStaff[]
     */
    public function getOtherStaff(): Collection
    {
        return $this->otherStaff;
    }

    public function addOtherStaff(OtherStaff $otherStaff): self
    {
        if (!$this->otherStaff->contains($otherStaff)) {
            $this->otherStaff[] = $otherStaff;
            $otherStaff->setTypeStaff($this);
        }

        return $this;
    }

    public function removeOtherStaff(OtherStaff $otherStaff): self
    {
        if ($this->otherStaff->contains($otherStaff)) {
            $this->otherStaff->removeElement($otherStaff);
            // set the owning side to null (unless already changed)
            if ($otherStaff->getTypeStaff() === $this) {
                $otherStaff->setTypeStaff(null);
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
