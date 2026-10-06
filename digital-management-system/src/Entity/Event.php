<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * An event happening at a certain time and location, such as a concert, lecture, or festival. Ticketing information may be added via the \[\[offers\]\] property. Repeated events may be structured as separate Event objects.
 *
 * @see http://schema.org/Event Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Event",
 *  attributes={
 *      "normalization_context" = {
 *          "groups"= {
 *               "thing"
 *               , "identifier"
 *               , "name"
 *               , "person"
 *               , "event"
 *           },"datetime_format"="Y-m-d"
 *      }
 *  },
 *     collectionOperations={"get"={"method"="GET"}, "post"={"method"="POST"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 * @ApiFilter(OrderFilter::class, properties={"id", "beginAt", "endAt"},
 *  arguments={
 *      "orderParameterName"="order"
 *  }
 * )
 * @ApiFilter(DateFilter::class, properties={"beginAt", "endAt"})
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Event
{
    use IdentifiableTrait
        ;
    use ThingTrait
        // , SluggableNameTrait
        ;
    use TimestampableTrait
        ;
    use AdministrableTrait
    ;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     * @Groups("event")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     * @Groups("event")
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_at", type="datetime")
     * @Groups("event")
     *
     * @Assert\NotBlank(message="Enter a rental start date")
     */
    private $beginAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime")
     * @Groups("event")
     *
     * @Assert\NotBlank(message="Enter a rental end date")
     */
    private $endAt;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="events")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=true)
     * @Groups("event")
     *
     * @ Assert\NotBlank(message="Select a tenant")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="rentals")
     * @ORM\JoinColumn(name="accommodation_id", referencedColumnName="id", nullable=true)
     *
     * @Groups("event")
     *
     * @ Assert\NotBlank(message="Select an accommodation")
     */
    private $accommodation;

    /**
     * One Event has One Location.
     *
     * @ORM\ManyToOne(targetEntity="RentalType")
     * @ORM\JoinColumn(name="rental_type_id", referencedColumnName="id")
     * @Groups("event")
     *
     * @ Assert\NotBlank(message="Select a rental type")
     */
    private $rentalType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("event")
     */
    private $depositAmount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("event")
     */
    private $depositStatus = false;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentObject")
     * @Groups("event")
     */
    private $rentalAgreement;

    /**
     * @Vich\UploadableField(mapping="uploads_document_files", fileNameProperty="document")
     *
     * @Assert\File(
     *     maxSize = "8M",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     * @Groups("event")
     */
    private $documentFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     * @Groups("event")
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccommodationNature", inversedBy="events")
     * @Groups("event")
     * @ApiSubresource
     */
    private $accommodationNature;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceTracking", mappedBy="event", cascade= { "remove" })
     * @Groups("event")
     * @ApiSubresource
     */
    private $trackings;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="events")
     * 
     * @Groups("event")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="event")
     * @Groups("event")
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("event")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("event")
     */
    private $details;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("event")
     */
    private $respondAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $numberOfAdults;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $numberOfChildren;

    /**
     * @ORM\OneToMany(targetEntity=HotelTypicalDayElement::class, mappedBy="event")
     */
    private $hotelTypicalDayElements;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="events")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class)
     */
    private $category;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->hotelTypicalDayElements = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return event
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTimeInterface $beginAt = null): void
    {
        $this->beginAt = $beginAt;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt = null): void
    {
        $this->endAt = $endAt;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     *
     * @return \App\Entity\Person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param mixed $accommodation
     *
     * @return \App\Entity\Accommodation
     */
    public function setAccommodation(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * Set the value of One Event has One Location.
     *
     * @param mixed rentalType
     *
     * @return self
     */
    public function setRentalType($rentalType)
    {
        $this->rentalType = $rentalType;

        return $this;
    }

    /**
     * Get the value of One Event has One Location.
     *
     * @return mixed
     */
    public function getRentalType()
    {
        return $this->rentalType;
    }

    public function getDepositAmount(): ?string
    {
        return $this->depositAmount;
    }

    public function setDepositAmount(?string $depositAmount): self
    {
        $this->depositAmount = $depositAmount;

        return $this;
    }

    public function getDepositStatus(): ?bool
    {
        return $this->depositStatus;
    }

    public function setDepositStatus(?bool $depositStatus): self
    {
        $this->depositStatus = $depositStatus;

        return $this;
    }

    public function getRentalAgreement(): ?DocumentObject
    {
        return $this->rentalAgreement;
    }

    public function setRentalAgreement(?DocumentObject $rentalAgreement): void
    {
        $this->rentalAgreement = $rentalAgreement;
    }

    public function getAccommodationNature(): ?AccommodationNature
    {
        return $this->accommodationNature;
    }

    public function setAccommodationNature(?AccommodationNature $accommodationNature): self
    {
        $this->accommodationNature = $accommodationNature;

        return $this;
    }

    /**
     * Set the value of Document.
     *
     * @param File document
     *
     * @return self
     */
    public function setDocument(File $document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get the value of Document.
     *
     * @return File
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return File
     */
    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * @param File $documentFile
     */
    public function setDocumentFile($documentFile)
    {
        $this->documentFile = $documentFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($documentFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getTrackings()
    {
        return $this->trackings;
    }

    /**
     * @param \App\Entity\InvoiceTracking $tracking
     */
    public function addTracking($tracking)
    {
        if ($this->trackings->contains($tracking)) {
            return;
        }

        $tracking->addEvent($this);
        $this->trackings->add($tracking);
    }

    /**
     * @param \App\Entity\InvoiceTracking $tracking
     */
    public function removeTracking($tracking)
    {
        if (!$this->trackings->contains($tracking)) {
            return;
        }

        $this->trackings->removeElement($tracking);
        $tracking->removeEvent($this);
    }

     /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        $this->rooms->removeElement($room);

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setEvent($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getEvent() === $this) {
                $message->setEvent(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

  

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getRespondAt(): ?\DateTimeInterface
    {
        return $this->respondAt;
    }

    public function setRespondAt(\DateTimeInterface $respondAt = null): void
    {
        $this->respondAt = $respondAt;
    }

    public function getNumberOfAdults(): ?int
    {
        return $this->numberOfAdults;
    }

    public function setNumberOfAdults(?int $numberOfAdults): self
    {
        $this->numberOfAdults = $numberOfAdults;

        return $this;
    }

    public function getNumberOfChildren(): ?int
    {
        return $this->numberOfChildren;
    }

    public function setNumberOfChildren(?int $numberOfChildren): self
    {
        $this->numberOfChildren = $numberOfChildren;

        return $this;
    }

    /**
     * @return Collection|HotelTypicalDayElement[]
     */
    public function getHotelTypicalDayElements(): Collection
    {
        return $this->hotelTypicalDayElements;
    }

    public function addHotelTypicalDayElement(HotelTypicalDayElement $hotelTypicalDayElement): self
    {
        if (!$this->hotelTypicalDayElements->contains($hotelTypicalDayElement)) {
            $this->hotelTypicalDayElements[] = $hotelTypicalDayElement;
            $hotelTypicalDayElement->setEvent($this);
        }

        return $this;
    }

    public function removeHotelTypicalDayElement(HotelTypicalDayElement $hotelTypicalDayElement): self
    {
        if ($this->hotelTypicalDayElements->removeElement($hotelTypicalDayElement)) {
            // set the owning side to null (unless already changed)
            if ($hotelTypicalDayElement->getEvent() === $this) {
                $hotelTypicalDayElement->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCategory(): ?Tag
    {
        return $this->category;
    }

    public function setCategory(?Tag $category): self
    {
        $this->category = $category;

        return $this;
    }
}
