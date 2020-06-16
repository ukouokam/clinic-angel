<?php

namespace App\Entity\Person\Staff\OtherStaff;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Person;
use App\Entity\Person\User\User;
use App\Repository\Person\Staff\OtherStaff\OtherStaffRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OtherStaffRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *     "groups" = {"other_staffs_read"}
 *     }
 * )
 * @ORM\Table(
 *     name="others_staff",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"person_id"})
 * })
 */
class OtherStaff
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"other_staffs_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Person::class, inversedBy="otherStaff", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"other_staffs_read"})
     * @Assert\NotBlank(message="La personne est requise")
     */
    private ?Person $person = null;

    /**
     * @ORM\ManyToOne(targetEntity=OtherTypeStaff::class, inversedBy="otherStaff")
     */
    private ?OtherTypeStaff $typeStaff = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"other_staffs_read"})
     */
    private ?string $comment = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"other_staffs_read"})
     * @Assert\NotBlank(message="La date de création est requise et doit être au format YYYY-MM-DD H:i:s")
     */
    private ?DateTimeInterface $saveAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="otherStaffsCreated")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"other_staffs_read"})
     * @Assert\NotBlank(message="Vous devez être connecté pour continuer")
     */
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups("other_staffs_read")
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return !is_null($this->person) ? $this->person->getFullName() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return DateTimeInterface|null
     */
    public function getBornAt(): ?DateTimeInterface
    {
        return !is_null($this->person) ? $this->person->getBornAt() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getPostal(): ?string
    {
        return !is_null($this->person) ? $this->person->getPostal() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return !is_null($this->person) ? $this->person->getPhoneNumber() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return !is_null($this->person) ? $this->person->getSlug() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getGender(): ?string
    {
        return !is_null($this->person) ? $this->person->getGender() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        return !is_null($this->person) ? $this->person->getIdentityNumber() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        return !is_null($this->person) ? $this->person->isValidIdentityCard() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        return !is_null($this->person) ? $this->person->getAge() : null;
    }

    /**
     * @Groups({"other_staffs_read"})
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
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getTypeStaffName(): ?string
    {
        return !is_null($this->typeStaff) ? $this->typeStaff->getName() : null;
    }

    public function getTypeStaff(): ?OtherTypeStaff
    {
        return $this->typeStaff;
    }

    public function setTypeStaff(?OtherTypeStaff $typeStaff): self
    {
        $this->typeStaff = $typeStaff;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
     * @Groups({"other_staffs_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return !is_null($this->createdBy) ? $this->createdBy->getFullName() ?? $this->createdBy->getUsername() : null;
    }
}
