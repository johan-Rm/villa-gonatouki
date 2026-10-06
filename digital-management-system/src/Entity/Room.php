<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A room is a distinguishable space within a structure, usually separated from other spaces by interior walls. (Source: Wikipedia, the free encyclopedia, see <http://en.wikipedia.org/wiki/Room>).
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/Room Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Room",
 *      attributes={
 *          "normalization_context" = {
 *              "groups"= { "room", "media_filename", "thing", "hotel_amenity", "tag", "name", "gallery" }
 *          }
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Room
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableTrait;
    use AdministrableTrait
    ;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @Groups("room")
     */
    private $slug;

    /**
     * @var string|null an alias for the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/alternateName")
     *
     * @Groups({"room", "collection:room"})
     */
    private $alternateName;

    /**
     * @var MediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Groups({"room", "collection:room"})
     * @ApiSubresource
     *
     * @Assert\NotBlank(message="Select the main image room")
     */
    private $primaryImage;

    /**
     * @var MediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Groups({"room", "collection:room"})
     * @ApiSubresource
     */
    private $secondaryImage;

    /**
     * @ORM\Column(name="maximumOccupants", type="smallint", nullable=true)
     * @Groups("room")
     *
     * @ Assert\NotBlank(message="Enter the number of occupants")
     */
    private $maximumOccupants;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", length=6, nullable=true)
     * @Groups({"room", "collection:accommodation"})
     *
     * @Assert\NotBlank(message="Enter the price of the property")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("room")
     */
    private $labelBgTransparent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups("room")
     */
    private $numberOfRooms;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelAmenity", inversedBy="rooms")
     *
     * @Groups("room")
     */
    private $amenities;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="rooms")
     *
     * @Groups("room")
     */
    private $gallery;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelAmenity", inversedBy="lounges")
     * @ORM\JoinTable(name="lounge_hotel_amenity")
     *
     * @Groups("room")
     */
    private $loungeAmenities;

    /**
     * @ORM\ManyToMany(targetEntity=AggregateOffer::class, inversedBy="rooms", cascade={"persist"})
     *
     * @Groups("room")
     */
    private $offers;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="rooms")
     *
     * @Groups("room")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="rooms")
     */
    private $events;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Groups("room")
     */
    private $minimumOccupants;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $DescriptionResume;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
        $this->loungeAmenities = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setAlternateName(?string $alternateName): void
    {
        $this->alternateName = $alternateName;
    }

    public function getAlternateName(): ?string
    {
        return $this->alternateName;
    }

    public function setPrimaryImage(?MediaObject $primaryImage): void
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage(): ?MediaObject
    {
        return $this->primaryImage;
    }

    public function setSecondaryImage(?MediaObject $secondaryImage): void
    {
        $this->secondaryImage = $secondaryImage;
    }

    public function getSecondaryImage(): ?MediaObject
    {
        return $this->secondaryImage;
    }

    /**
     * Get the value of MaximumOccupants.
     *
     * @return int
     */
    public function getMaximumOccupants()
    {
        return $this->maximumOccupants;
    }

    /**
     * Set the value of MaximumOccupants.
     *
     * @param int MaximumOccupants
     *
     * @return self
     */
    public function setMaximumOccupants($maximumOccupants)
    {
        $this->maximumOccupants = $maximumOccupants;

        return $this;
    }

    /**
     * Set the value of Price.
     *
     * @param float price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of Price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getLabelBgTransparent(): ?bool
    {
        return $this->labelBgTransparent;
    }

    public function setLabelBgTransparent(bool $labelBgTransparent): self
    {
        $this->labelBgTransparent = $labelBgTransparent;

        return $this;
    }

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(?int $numberOfRooms): self
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    /**
     * @return Collection|HotelAmenity[]
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(HotelAmenity $amenity): self
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
        }

        return $this;
    }

    public function removeAmenity(HotelAmenity $amenity): self
    {
        if ($this->amenities->contains($amenity)) {
            $this->amenities->removeElement($amenity);
        }

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Collection|HotelAmenity[]
     */
    public function getLoungeAmenities(): Collection
    {
        return $this->loungeAmenities;
    }

    public function addLoungeAmenity(HotelAmenity $loungeAmenity): self
    {
        if (!$this->loungeAmenities->contains($loungeAmenity)) {
            $this->loungeAmenities[] = $loungeAmenity;
        }

        return $this;
    }

    public function removeLoungeAmenity(HotelAmenity $loungeAmenity): self
    {
        $this->loungeAmenities->removeElement($loungeAmenity);

        return $this;
    }

    /**
     * @return Collection|AggregateOffer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(AggregateOffer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
        }

        return $this;
    }

    public function removeOffer(AggregateOffer $offer): self
    {
        $this->offers->removeElement($offer);

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

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setRoom($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRoom() === $this) {
                $event->setRoom(null);
            }
        }

        return $this;
    }

    public function getMinimumOccupants(): ?int
    {
        return $this->minimumOccupants;
    }

    public function setMinimumOccupants(int $minimumOccupants): self
    {
        $this->minimumOccupants = $minimumOccupants;

        return $this;
    }

    public function getDescriptionResume(): ?string
    {
        return $this->DescriptionResume;
    }

    public function setDescriptionResume(?string $DescriptionResume): self
    {
        $this->DescriptionResume = $DescriptionResume;

        return $this;
    }
}
