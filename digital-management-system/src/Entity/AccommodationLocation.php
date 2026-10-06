<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/? Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/?",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 * @ApiFilter(SearchFilter::class,
 *   properties={
 *          "slug": "exact"
 *       }
 *   )
 * @ ORM\Entity(repositoryClass="App\Repository\AccommodationLocationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationLocation
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
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = 1;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getName();
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
