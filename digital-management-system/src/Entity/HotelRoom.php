<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * A hotel room is a single room in a hotel.
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/HotelRoom Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/HotelRoom",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}},
 *      attributes={
 *          "normalization_context" = {
 *              "groups"= { "hotel_room", "name" }
 *          }
 *      }
 *  )
 */
class HotelRoom
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableTrait;
    use AdministrableTrait
    ;

    /**
     * @var string|null an alias for the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/alternateName")
     *
     * @Groups("hotel_room")
     */
    private $alternateName;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     * @Groups("hotel_room")
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setAlternateName(?string $alternateName): void
    {
        $this->alternateName = $alternateName;
    }

    public function getAlternateName(): ?string
    {
        return $this->alternateName;
    }
}
