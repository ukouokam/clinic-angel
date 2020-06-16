<?php

namespace App\Entity\Person\Staff\Doctor;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Person;
use App\Entity\Person\User\User;
use App\Repository\Person\Staff\Doctor\DoctorRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *          "groups" = {"doctors_read"}
 *     }
 * )
 * @ORM\Table(
 *     name="doctors",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"person_id"})}
 * )
 */
class Doctor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"doctors_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="doctor", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"doctors_read"})
     * @Assert\NotBlank(message="La personne est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\ManyToOne(targetEntity=DoctorCategory::class, inversedBy="doctors")
     */
    private ?DoctorCategory $category;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"doctors_read"})
     * @Assert\NotBlank(message="La date de création est obligatoire et au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $saveAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="doctorsCreated")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"doctors_read"})
     * @Assert\NotBlank(message="Vous devez être connecté pour effectuer cette tâche")
     */
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("doctors_read")
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return !is_null($this->person) ? $this->person->getFullName() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return !is_null($this->person) ? $this->person->getBornAt() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return !is_null($this->person) ? $this->person->getPostal() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return !is_null($this->person) ? $this->person->getPhoneNumber() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return !is_null($this->person) ? $this->person->getSlug() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getGender(): ?string
    {
        return !is_null($this->person) ? $this->person->getGender() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return !is_null($this->person) ? $this->person->getIdentityNumber() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return !is_null($this->person) ? $this->person->isValidIdentityCard() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return !is_null($this->person) ? $this->person->getAge() : null;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return !is_null($this->person) ? $this->person->getBirthPlace() : null;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getCategoryName(): ?string
    {
        return !is_null($this->category) ? $this->category->getName() : null;
    }

    public function getCategory(): ?DoctorCategory
    {
        return $this->category;
    }

    public function setCategory(?DoctorCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSaveAt(): ?DateTimeInterface
    {
        return $this->saveAt;
    }

    /**
     * @param DateTimeInterface|string|null $saveAt
     * @return $this
     */
    public function setSaveAt($saveAt): self
    {
        try {
            $this->saveAt = is_string($saveAt) ? new DateTime($saveAt) : $saveAt;
        } catch (Exception $exception) {
            $this->saveAt = null;
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
     * @Groups({"doctors_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
