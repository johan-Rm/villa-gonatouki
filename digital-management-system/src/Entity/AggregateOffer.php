<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\OfferTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * When a single product is associated with multiple offers (for example, the same pair of shoes is offered by different merchants), then AggregateOffer can be used.
 *
 * @see http://schema.org/AggregateOffer Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/AggregateOffer")
 * @ORM\HasLifecycleCallbacks()
 */
class AggregateOffer
{
    use IdentifiableTrait
        ;
    use OfferTrait
        ;
    use AdministrableTrait
        ;
    use TimestampableTrait
    ;

    /**
     * @var float|null the highest price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/highPrice")
     *
     * @Groups("room")
     */
    private $highPrice;

    /**
     * @var float|null the lowest price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/lowPrice")
     *
     * @Groups("room")
     */
    private $lowPrice;

    /**
     * @var int|null the number of offers for the product
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ApiProperty(iri="http://schema.org/offerCount")
     *
     * @Groups("room")
     */
    private $offerCount;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="offers")
     */
    private $rooms;

    /**
     * @ var Collection<Offer>|null An additional offer that can only be obtained in combination with the first base offer (e.g. supplements and extensions that are available for a surcharge).
     *
     * @ ORM\ManyToMany(targetEntity="App\Entity\AggregateOffer")
     * @ ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ ApiProperty(iri="http://schema.org/addOn")
     */

    /**
     * @var float|null the addOn price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/addOn")
     *
     * @Groups("room")
     */
    private $addOn;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        // $this->addOn = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param float|null $highPrice
     */
    public function setHighPrice($highPrice): void
    {
        $this->highPrice = $highPrice;
    }

    /**
     * @return float|null
     */
    public function getHighPrice()
    {
        return $this->highPrice;
    }

    /**
     * @param float|null $lowPrice
     */
    public function setLowPrice($lowPrice): void
    {
        $this->lowPrice = $lowPrice;
    }

    /**
     * @return float|null
     */
    public function getLowPrice()
    {
        return $this->lowPrice;
    }

    public function setOfferCount(?int $offerCount): void
    {
        $this->offerCount = $offerCount;
    }

    public function getOfferCount(): ?int
    {
        return $this->offerCount;
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
            $room->addOffer($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeOffer($this);
        }

        return $this;
    }

    // public function addAddOn(AggregateOffer $addOn): void
    // {
    //     $this->addOn[] = $addOn;
    // }

    // public function removeAddOn(AggregateOffer $addOn): void
    // {
    //     $this->addOn->removeElement($addOn);
    // }

    // public function getAddOn(): Collection
    // {
    //     return $this->addOn;
    // }

    /**
     * @param float|null $addOn
     */
    public function setAddOn($addOn): void
    {
        $this->addOn = $addOn;
    }

    /**
     * @return float|null
     */
    public function getAddOn()
    {
        return $this->addOn;
    }
}
