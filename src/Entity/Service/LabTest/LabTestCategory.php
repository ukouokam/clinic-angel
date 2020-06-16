<?php

namespace App\Entity\Service\LabTest;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\User\User;
use App\Repository\Service\LabTest\LabTestCategoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabTestCategoryRepository::class)
 * @ORM\Table(name="lab_tests_categories")
 * @ApiResource()
 */
class LabTestCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la catégorie d'analyse ne doit pas être vide")
     */
    private ?string $wording = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="labTestCategories")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="L'utilisateur est requis")
     */
    private ?User $createdBy = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\OneToMany(targetEntity=LabTest::class, mappedBy="category")
     * @var LabTest[]|ArrayCollection
     */
    private $labTests;

    public function __construct()
    {
        $this->labTests = new ArrayCollection();
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

    /**
     * @return Collection|LabTest[]
     */
    public function getLabTests(): Collection
    {
        return $this->labTests;
    }

    public function addLabTest(LabTest $labTest): self
    {
        if (!$this->labTests->contains($labTest)) {
            $this->labTests[] = $labTest;
            $labTest->setCategory($this);
        }

        return $this;
    }

    public function removeLabTest(LabTest $labTest): self
    {
        if ($this->labTests->contains($labTest)) {
            $this->labTests->removeElement($labTest);
            // set the owning side to null (unless already changed)
            if ($labTest->getCategory() === $this) {
                $labTest->setCategory(null);
            }
        }

        return $this;
    }
}
