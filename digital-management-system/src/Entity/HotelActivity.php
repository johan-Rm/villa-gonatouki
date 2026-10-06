<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * HotelActivity.
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/?",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "normalization_context" = {
 *              "groups"= { "thing", "creative", "hotel_activity", "media", "tag" }
 *          }
 *      }
 *  )
 * @ORM\HasLifecycleCallbacks()
 */
class HotelActivity
{
    use IdentifiableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableTrait;
    use AdministrableTrait
    ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups("hotel_activity")
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups("hotel_activity")
     */
    private $value;

    /**
     * @var MediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Groups({"hotel_activity", "collection:hotel_activity"})
     * @ApiSubresource
     *
     * @Assert\NotBlank(message="Select the main image hotel_activity")
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     * @Groups("hotel_activity")
     * @ApiSubresource
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="hotelActivities")
     *
     * @Groups("hotel_activity")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     * @Groups("hotel_activity")
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel().' => '.$this->getValue();
    }

    public function getSlug()
    {
        return $this->slug;
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

    public function setPrimaryImage(?MediaObject $primaryImage): void
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage(): ?MediaObject
    {
        return $this->primaryImage;
    }

    public function setCategory(?Tag $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): ?Tag
    {
        return $this->category;
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
