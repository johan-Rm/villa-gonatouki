<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/? Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/?",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "normalization_context" = {
 *              "groups"= {  "thing", "creative", "hotel_amenity", "tag" }
 *          }
 *      }
 *  )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class HotelAmenity
{
    use IdentifiableTrait
        ;
    use ThingTrait;
    use TimestampableTrait
        ;
    use AdministrableTrait
    ;

    /**
     * @var bool
     *
     * @ORM\Column(name="withPicto", type="boolean", nullable=true)
     *
     * @Groups("hotel_amenity")
     */
    private $withPicto;

    /**
     * @var bool
     *
     * @ORM\Column(name="slugPicto", type="string", nullable=true)
     *
     * @Groups("hotel_amenity")
     */
    private $slugPicto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag", cascade={"persist"})
     *
     * @Groups("hotel_amenity")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="hotelAmenities")
     *
     * @Groups("hotel_amenity")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", mappedBy="amenities")
     *
     * @ Groups("hotel_amenity")
     */
    private $rooms;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", mappedBy="loungeAmenities")
     */
    private $lounges;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups("hotel_amenity")
     */
    private $moreInfo;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     * @Groups("hotel_amenity")
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->lounges = new ArrayCollection();
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
     * Set withPicto.
     *
     * @param string $withPicto
     *
     * @return AccommodationAmenity
     */
    public function setWithPicto($withPicto)
    {
        $this->withPicto = $withPicto;

        return $this;
    }

    /**
     * Get withPicto.
     *
     * @return string
     */
    public function getWithPicto()
    {
        return $this->withPicto;
    }

    /**
     * Set slugPicto.
     *
     * @param string $slugPicto
     *
     * @return AccommodationAmenity
     */
    public function setSlugPicto($slugPicto)
    {
        $this->slugPicto = $slugPicto;

        return $this;
    }

    /**
     * Get slugPicto.
     *
     * @return string
     */
    public function getSlugPicto()
    {
        return $this->slugPicto;
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
            $room->addAmenity($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            $room->removeAmenity($this);
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getLounges(): Collection
    {
        return $this->lounges;
    }

    public function addLounge(Room $lounge): self
    {
        if (!$this->lounges->contains($lounge)) {
            $this->lounges[] = $lounge;
            $lounge->addLoungeAmenity($this);
        }

        return $this;
    }

    public function removeLounge(Room $lounge): self
    {
        if ($this->lounges->removeElement($lounge)) {
            $lounge->removeLoungeAmenity($this);
        }

        return $this;
    }

    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }

    public function setMoreInfo(string $moreInfo): self
    {
        $this->moreInfo = $moreInfo;

        return $this;
    }
}
