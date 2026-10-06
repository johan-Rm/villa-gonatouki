<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * hotelTypicalDay.
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/?",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "normalization_context" = {
 *              "groups"= { "hotel_typical_day", "media_filename", "tag", "thing", "event" }
 *          }
 *      }
 *  )
 * @ORM\HasLifecycleCallbacks()
 */
class HotelTypicalDay
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableTrait;
    use AdministrableTrait
    ;

    /**
     * @ORM\OneToMany(targetEntity="HotelTypicalDayElement", mappedBy="hotelTypicalDay", cascade= { "remove" })
     * @Groups("hotel_typical_day")
     */
    private $elements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     *
     * @Groups("hotel_typical_day")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="hotelTypicalDays")
     *
     * @Groups("hotel_typical_day")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     *
     * @Groups("hotel_typical_day")
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param \App\Entity\HotelTypicalDayElement $element
     */
    public function addElement($element)
    {
        if ($this->elements->contains($element)) {
            return;
        }

        $element->addHotelTypicalDay($this);
        $this->elements->add($element);
    }

    /**
     * @param \App\Entity\HotelTypicalDayElement $element
     */
    public function removeElement($element)
    {
        if (!$this->elements->contains($element)) {
            return;
        }

        $this->elements->removeElement($element);
        $element->removeHotelTypicalDay($this);
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
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

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
}
