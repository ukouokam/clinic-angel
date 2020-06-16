<?php

namespace App\Entity\Person;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\Staff\Cashier\Cashier;
use App\Entity\Person\Staff\Doctor\Doctor;
use App\Entity\Person\Staff\Nurse\Nurse;
use App\Entity\Person\Staff\OtherStaff\OtherStaff;
use App\Entity\Person\Staff\Technician\Technician;
use App\Entity\Person\User\User;
use App\Repository\Person\PersonRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="persons")
 * @ApiResource(
 *     normalizationContext={
 *          "groups" = {"persons_read"}
 *     }
 * )
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"persons_read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"persons_read"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="2", minMessage="Le nom de la personne doit avoir au moins 02 caractères",
     *     max="255", maxMessage="Le nom doit avoir au maximum 255 caratères"
     * )
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"persons_read"})
     * @Assert\NotBlank(message="La date de naissance n'est pas au format valide (YYYY-MM-DD H:i:s)")
     */
    private ?DateTimeInterface $bornAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"persons_read"})
     * @Assert\NotBlank(message="La date de création de la personne n'est pas au format valide (YYYY-MM-DD H:i:s)")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"persons_read"})
     */
    private ?string $postal = null;

    /**
     * @ORM\OneToMany(targetEntity=FirstName::class, mappedBy="person")
     * @var FirstName[]|ArrayCollection
     */
    private $firstNames;

    /**
     * @ORM\OneToMany(targetEntity=PhoneNumber::class, mappedBy="person")
     * @var PhoneNumber[]|ArrayCollection
     */
    private $phoneNumbers;

    /**
     * @ORM\ManyToOne(targetEntity=Sex::class, inversedBy="people")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Choisissez le sexe de la personne")
     */
    private ?Sex $sex = null;

    /**
     * @ORM\OneToMany(targetEntity=IdentityCard::class, mappedBy="person")
     * @var IdentityCard[]|ArrayCollection
     */
    private $identityCards;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"persons_read"})
     */
    private ?string $slug = null;

    /**
     * @ORM\OneToOne(targetEntity=Patient::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?Patient $patient = null;

    private array $age = [];

    /**
     * @ORM\OneToOne(targetEntity=Doctor::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?Doctor $doctor = null;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?User $userPerson = null;

    /**
     * @ORM\OneToOne(targetEntity=Technician::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?Technician $technician = null;

    /**
     * @ORM\OneToOne(targetEntity=Nurse::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?Nurse $nurse = null;

    /**
     * @ORM\OneToOne(targetEntity=OtherStaff::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?OtherStaff $otherStaff = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"persons_read"})
     * @Assert\Length(
     *     max="255", maxMessage="L'adresse doit avoir au maximum 255 caratères"
     * )
     */
    private ?string $address = null;

    /**
     * @ORM\ManyToOne(targetEntity=Civility::class, inversedBy="people")
     * @todo Remettre nullable à false
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Civility $civility = null;

    /**
     * @ORM\ManyToOne(targetEntity=MaritalStatus::class, inversedBy="people")
     * @todo Remettre nullable à false
     * @ORM\JoinColumn(nullable=false)
     */
    private ?MaritalStatus $maritalStatus = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"persons_read"})
     * @Assert\Length(max="255", maxMessage="Le Lieu de naissance doit au maximum 255 caractères")
     */
    private ?string $birthPlace = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="peopleCreated")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Vous devez préciser l'utilisateur qui crée la personne")
     * @todo A la fin, mettre "nullable = false" et relancer les migrations
     */
    private ?User $createdBy = null;

    /**
     * @ORM\OneToOne(targetEntity=Cashier::class, mappedBy="person", cascade={"persist", "remove"})
     */
    private ?Cashier $cashier = null;

    /**
     * @ORM\OneToMany(targetEntity=Email::class, mappedBy="person")
     * @var Email[]|ArrayCollection
     */
    private $emails;

    public function __construct()
    {
        $this->firstNames = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->identityCards = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getAgeArray():array
    {
        if (empty($this->bornAt)) {
            return $this->age;
        }
        $today = new DateTime('now');
        $interval = $today->diff($this->bornAt);
        $age = explode(',', $interval->format('%Y,%M,%D,%H,%I,%S,%F'));
        $this->age['years'] = $age[0];
        $this->age['months'] = $age[1];
        $this->age['days'] = $age[2];
        $this->age['hours'] = $age[3];
        $this->age['minutes'] = $age[4];
        $this->age['seconds'] = $age[5];
        $this->age['micro_sec'] = $age[6];
        return $this->age;
    }

    /**
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getAge(): ?string
    {
        $age = $this->getAgeArray();
        if (!empty($age)){
            $years = $age['years'];
            $months = $age['months'];
            $days = $age['days'];
            return "$years ans, $months mois et $days jours";
        }
        return null;
    }

    /**
     * @Groups("persons_read")
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return array_reduce($this->firstNames->toArray(), static function(?string $initial, FirstName $firstName){
            if (is_null($initial)) {
                return $firstName->getValue();
            }
            return trim($initial . ' ' . $firstName->getValue());
        }, null);
    }

    /**
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return trim($this->civility->getName() . ' ' . $this->lastName . ' ' . $this->getFirstName());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBornAt(): ?DateTimeInterface
    {
        return $this->bornAt;
    }

    /**
     * @param DateTimeInterface|string|null $bornAt
     * @return $this
     */
    public function setBornAt($bornAt): self
    {
        try {
            $this->bornAt = is_string($bornAt) ? new DateTime($bornAt) : $bornAt;
        } catch (Exception $exception) {
            $this->bornAt = null;
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

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * @return Collection|FirstName[]
     */
    public function getFirstNames(): Collection
    {
        return $this->firstNames;
    }

    public function addFirstName(FirstName $firstName): self
    {
        if (!$this->firstNames->contains($firstName)) {
            $this->firstNames[] = $firstName;
            $firstName->setPerson($this);
        }

        return $this;
    }

    public function removeFirstName(FirstName $firstName): self
    {
        if ($this->firstNames->contains($firstName)) {
            $this->firstNames->removeElement($firstName);
            // set the owning side to null (unless already changed)
            if ($firstName->getPerson() === $this) {
                $firstName->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        $phoneNumbers = array_map(static function(PhoneNumber $phoneNumber) {
            return ($phoneNumber->getIsUse()) ? $phoneNumber->getValue(): null;
        }, $this->phoneNumbers->toArray());
        //Les éléments null qui peuvent y apparaître, on les supprime en filtrant le tableau
        $phoneNumbers = array_filter($phoneNumbers, static function($key) use ($phoneNumbers) {
            return (!is_null($phoneNumbers[$key]));
        }, ARRAY_FILTER_USE_KEY);

        $phoneNumbers = implode(', ', $phoneNumbers);
        return ($phoneNumbers !== '') ? $phoneNumbers : null ;
    }

    /**
     * @return Collection|PhoneNumber[]
     */
    public function getPhoneNumbers(): Collection
    {
        return $this->phoneNumbers;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): self
    {
        if (!$this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers[] = $phoneNumber;
            $phoneNumber->setPerson($this);
        }

        return $this;
    }

    public function removePhoneNumber(PhoneNumber $phoneNumber): self
    {
        if ($this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->removeElement($phoneNumber);
            // set the owning side to null (unless already changed)
            if ($phoneNumber->getPerson() === $this) {
                $phoneNumber->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->sex->getSexName();
    }

    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    public function setSex(?Sex $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @Groups({"persons_read"})
     * @return int|string|null
     */
    public function getIdentityNumber()
    {
        $cards = $this->identityCards->toArray();
        /** @var IdentityCard|IdentityCard[]|null $cards */
        $cards = array_filter($cards, static function($key) use ($cards) {
            return ($cards[$key]->getActualCard() === true);
        }, ARRAY_FILTER_USE_KEY);

        if ($cards instanceof IdentityCard) {
            return $cards->getIdentityNumber();
        }
        if (is_array($cards)) {
            return array_reduce($cards, static function(?string $initial, IdentityCard $card){
                if (is_null($initial)) {
                    return $card->getIdentityNumber();
                }
                return trim($card->getIdentityNumber() . ', ' . $initial);
            }, null);
        }
        return null;
    }

    /**
     * @Groups({"persons_read"})
     * @return bool|null
     */
    public function isValidIdentityCard(): ?bool
    {
        $identityNumber = $this->getIdentityNumber();
        if (is_null($identityNumber)) {
            return null;
        }
        if (is_int($identityNumber) || is_string($identityNumber)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|IdentityCard[]
     */
    public function getIdentityCards(): Collection
    {
        return $this->identityCards;
    }

    public function addCardNumber(IdentityCard $cardNumber): self
    {
        if (!$this->identityCards->contains($cardNumber)) {
            $this->identityCards[] = $cardNumber;
            $cardNumber->setPerson($this);
        }

        return $this;
    }

    public function removeCardNumber(IdentityCard $cardNumber): self
    {
        if ($this->identityCards->contains($cardNumber)) {
            $this->identityCards->removeElement($cardNumber);
            // set the owning side to null (unless already changed)
            if ($cardNumber->getPerson() === $this) {
                $cardNumber->setPerson(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): self
    {
        $this->patient = $patient;

        // set the owning side of the relation if necessary
        if ($patient->getPerson() !== $this) {
            $patient->setPerson($this);
        }

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(Doctor $doctor): self
    {
        $this->doctor = $doctor;

        // set the owning side of the relation if necessary
        if ($doctor->getPerson() !== $this) {
            $doctor->setPerson($this);
        }

        return $this;
    }

    public function getUserPerson(): ?User
    {
        return $this->userPerson;
    }

    public function setUserPerson(User $userPerson): self
    {
        $this->userPerson = $userPerson;

        // set the owning side of the relation if necessary
        if ($userPerson->getPerson() !== $this) {
            $userPerson->setPerson($this);
        }

        return $this;
    }

    public function getTechnician(): ?Technician
    {
        return $this->technician;
    }

    public function setTechnician(?Technician $technician): self
    {
        $this->technician = $technician;

        // set (or unset) the owning side of the relation if necessary
        $newPerson = null === $technician ? null : $this;
        if ($technician->getPerson() !== $newPerson) {
            $technician->setPerson($newPerson);
        }

        return $this;
    }

    public function getNurse(): ?Nurse
    {
        return $this->nurse;
    }

    public function setNurse(Nurse $nurse): self
    {
        $this->nurse = $nurse;

        // set the owning side of the relation if necessary
        if ($nurse->getPerson() !== $this) {
            $nurse->setPerson($this);
        }

        return $this;
    }

    public function getOtherStaff(): ?OtherStaff
    {
        return $this->otherStaff;
    }

    public function setOtherStaff(OtherStaff $otherStaff): self
    {
        $this->otherStaff = $otherStaff;

        // set the owning side of the relation if necessary
        if ($otherStaff->getPerson() !== $this) {
            $otherStaff->setPerson($this);
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(?string $birthPlace): self
    {
        $this->birthPlace = $birthPlace;

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
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getAuthor(): ?string
    {

        return !is_null($this->createdBy->getFullName()) ? $this->createdBy->getFullName() : $this->createdBy->getUsername();
    }

    public function getCashier(): ?Cashier
    {
        return $this->cashier;
    }

    public function setCashier(Cashier $cashier): self
    {
        $this->cashier = $cashier;

        // set the owning side of the relation if necessary
        if ($cashier->getPerson() !== $this) {
            $cashier->setPerson($this);
        }

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"persons_read"})
     */
    public function getCivilityName(): ?string
    {
        return $this->civility->getName();
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"persons_read"})
     */
    public function getMaritalStatusName(): ?string
    {
        return $this->maritalStatus->getName();
    }

    public function getMaritalStatus(): ?MaritalStatus
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(?MaritalStatus $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(Email $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->setPerson($this);
        }

        return $this;
    }

    public function removeEmail(Email $email): self
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
            // set the owning side to null (unless already changed)
            if ($email->getPerson() === $this) {
                $email->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"persons_read"})
     * @return string|null
     */
    public function getEmail(): ?string
    {
        $mails = $this->emails->toArray();
        if (count($mails) === 0) {
            return null;
        }
        return array_reduce($mails, static function (?string $initial, Email $email) {
            if (is_null($initial)) {
                return $email->getAddress();
            }
            return trim($email->getAddress() . ', ' . $initial);
        }, null);
    }
}
