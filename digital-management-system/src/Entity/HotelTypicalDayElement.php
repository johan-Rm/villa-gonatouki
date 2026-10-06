<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * HotelTypicalDayElement.
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/?",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 * @ORM\HasLifecycleCallbacks()
 */
class HotelTypicalDayElement
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use AdministrableTrait
    ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("hotel_typical_day")
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("hotel_typical_day")
     */
    private $value;

    /**
     * @Gedmo\Slug(fields={"value"}, updatable=false)
     * @ORM\Column(length=128, unique=true)
     * @Groups("hotel_typical_day")
     */
    private $slug;

    /**
     * @var MediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Groups({"hotel_typical_day", "collection:hotel_typical_day"})
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
     * @Groups({"hotel_typical_day", "collection:hotel_typical_day"})
     * @ApiSubresource
     */
    private $secondaryImage;

    /**
     * @ORM\ManyToOne(targetEntity="HotelTypicalDay", inversedBy="elements")
     * @ORM\JoinColumn(name="hotel_typical_day_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $hotelTypicalDay;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups("hotel_typical_day")
     */
    private $hours;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("hotel_typical_day")
     */
    private $labelBgTransparent;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups("hotel_typical_day")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     *
     * @Groups("hotel_typical_day")
     */
    private $isActive = true;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="hotelTypicalDayElements")
     *
     * @Groups("hotel_typical_day")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="hotelTypicalDayElements")
     *
     * @Groups("hotel_typical_day")
     */
    private $event;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getLabel().' => '.$this->getValue();
    }

    /**
     * Set the value of Label.
     *
     * @param mixed label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the value of Label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of Value.
     *
     * @param mixed value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of Value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set HotelTypicalDay.
     *
     * @param \App\Entity\HotelTypicalDay $hotelTypicalDay
     *
     * @return HotelTypicalDay
     */
    public function setHotelTypicalDay(HotelTypicalDay $hotelTypicalDay = null)
    {
        $this->hotelTypicalDay = $hotelTypicalDay;

        return $this;
    }

    /**
     * Get HotelTypicalDay.
     *
     * @return \App\Entity\HotelTypicalDay
     */
    public function getHotelTypicalDay()
    {
        return $this->hotelTypicalDay;
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

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(string $hours): self
    {
        $this->hours = $hours;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
