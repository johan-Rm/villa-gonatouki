<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing",
 *   attributes={
 *      "normalization_context"={"groups"={"tag"}}
 *  },
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
{
    use IdentifiableTrait
        // , ThingTrait
        // , SluggableNameTrait
        ;
    use TimestampableTrait
        ;
    use AdministrableTrait
    ;

    /**
     * @ORM\ManyToMany(targetEntity="WebPage", mappedBy="tags")
     */
    private $webPages;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity="Accommodation", mappedBy="tags")
     */
    private $accommodations;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     * @Groups("tag")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Groups("tag")
     * @Assert\NotNull
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     * @Groups("tag")
     */
    private $isActive = true;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelActivity", mappedBy="tags")
     */
    private $hotelActivities;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelAmenity", mappedBy="tags")
     */
    private $hotelAmenities;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelTypicalDay", mappedBy="tags")
     */
    private $hotelTypicalDays;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HotelService", mappedBy="tags")
     */
    private $hotelServices;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="category")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=HotelTypicalDayElement::class, mappedBy="category")
     */
    private $hotelTypicalDayElements;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="tags")
     */
    private $events;

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->webPages = new ArrayCollection();
        $this->accommodations = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->hotelActivities = new ArrayCollection();
        $this->hotelAmenities = new ArrayCollection();
        $this->hotelTypicalDays = new ArrayCollection();
        $this->hotelServices = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->hotelTypicalDayElements = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add WebPage.
     *
     * @param App\Entity\WebPage $webPage
     *
     * @return WebPage
     */
    public function addWebPage(WebPage $webPage): void
    {
        if ($this->webPages->contains($webPage)) {
            return;
        }

        $this->webPages->add($webPage);

        // Bidirectional Ownership
        $webPage->addTag($this);
    }

    /**
     * Remove WebPage.
     *
     * @param App\Entity\WebPage $webPage
     */
    public function removeWebPage(WebPage $webPage): void
    {
        // If the category does not exist in the collection, then we don't need to do anything
        if (!$this->webPages->contains($webPage)) {
            return;
        }

        // Remove category from the collection
        $this->webPages->removeElement($webPage);
        // Also remove this from the blog post collection of the category
        $webPage->removeTag($this);
    }

    /**
     * Get Galleries.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWebPages()
    {
        return $this->webPages;
    }

    /**
     * Add Accommodation.
     *
     * @param App\Entity\Accommodation $accommodation
     *
     * @return Accommodation
     */
    public function addAccommodation(Accommodation $accommodation): void
    {
        if ($this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->add($accommodation);

        // Bidirectional Ownership
        $accommodation->addTag($this);
    }

    /**
     * Remove Accommodation.
     *
     * @param App\Entity\Accommodation $acommodation
     */
    public function removeAccommodation(Accommodation $accommodation): void
    {
        if (!$this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->removeElement($accommodation);

        $accommodation->removeTag($this);
    }

    /**
     * Get Accommodations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccommodations()
    {
        return $this->accommodations;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addTag($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeTag($this);
        }

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * @return Collection|HotelActivity[]
     */
    public function getHotelActivities(): Collection
    {
        return $this->hotelActivities;
    }

    public function addHotelActivity(HotelActivity $hotelActivity): self
    {
        if (!$this->hotelActivities->contains($hotelActivity)) {
            $this->hotelActivities[] = $hotelActivity;
            $hotelActivity->addTag($this);
        }

        return $this;
    }

    public function removeHotelActivity(HotelActivity $hotelActivity): self
    {
        if ($this->hotelActivities->contains($hotelActivity)) {
            $this->hotelActivities->removeElement($hotelActivity);
            $hotelActivity->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|HotelAmenity[]
     */
    public function getHotelAmenities(): Collection
    {
        return $this->hotelAmenities;
    }

    public function addHotelAmenity(HotelAmenity $hotelAmenity): self
    {
        if (!$this->hotelAmenities->contains($hotelAmenity)) {
            $this->hotelAmenities[] = $hotelAmenity;
            $hotelAmenity->addTag($this);
        }

        return $this;
    }

    public function removeHotelAmenity(HotelAmenity $hotelAmenity): self
    {
        if ($this->hotelAmenities->contains($hotelAmenity)) {
            $this->hotelAmenities->removeElement($hotelAmenity);
            $hotelAmenity->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|HotelTypicalDay[]
     */
    public function getHotelTypicalDays(): Collection
    {
        return $this->hotelTypicalDays;
    }

    public function addHotelTypicalDay(HotelTypicalDay $hotelTypicalDay): self
    {
        if (!$this->hotelTypicalDays->contains($hotelTypicalDay)) {
            $this->hotelTypicalDays[] = $hotelTypicalDay;
            $hotelTypicalDay->addTag($this);
        }

        return $this;
    }

    public function removeHotelTypicalDay(HotelTypicalDay $hotelTypicalDay): self
    {
        if ($this->hotelTypicalDays->contains($hotelTypicalDay)) {
            $this->hotelTypicalDays->removeElement($hotelTypicalDay);
            $hotelTypicalDay->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|HotelService[]
     */
    public function getHotelServices(): Collection
    {
        return $this->hotelServices;
    }

    public function addHotelService(HotelService $hotelService): self
    {
        if (!$this->hotelServices->contains($hotelService)) {
            $this->hotelServices[] = $hotelService;
            $hotelService->addTag($this);
        }

        return $this;
    }

    public function removeHotelService(HotelService $hotelService): self
    {
        if ($this->hotelServices->contains($hotelService)) {
            $this->hotelServices->removeElement($hotelService);
            $hotelService->removeTag($this);
        }

        return $this;
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
            $room->setCategory($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getCategory() === $this) {
                $room->setCategory(null);
            }
        }

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
            $hotelTypicalDayElement->setCategory($this);
        }

        return $this;
    }

    public function removeHotelTypicalDayElement(HotelTypicalDayElement $hotelTypicalDayElement): self
    {
        if ($this->hotelTypicalDayElements->removeElement($hotelTypicalDayElement)) {
            // set the owning side to null (unless already changed)
            if ($hotelTypicalDayElement->getCategory() === $this) {
                $hotelTypicalDayElement->setCategory(null);
            }
        }

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
            $event->addTag($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeTag($this);
        }

        return $this;
    }
}
