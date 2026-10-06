<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
 *              "groups"= { "name", "hotel_service", "tag" }
 *          }
 *      }
 *  )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class HotelService
{
    use IdentifiableTrait
        ;
    use TimestampableTrait
        ;
    use SluggableNameTrait
        ;
    use AdministrableTrait
    ;

    /**
     * @var bool
     *
     * @ORM\Column(name="withPicto", type="boolean", nullable=true)
     *
     * @Groups("hotel_service")
     */
    private $withPicto;

    /**
     * @var bool
     *
     * @ORM\Column(name="slugPicto", type="string", nullable=true)
     *
     * @Groups("hotel_service")
     */
    private $slugPicto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     *
     * @Groups("hotel_service")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="hotelServices")
     *
     * @Groups("hotel_service")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups("hotel_service")
     */
    private $moreInfo;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
