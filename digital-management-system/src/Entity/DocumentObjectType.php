<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
 * @ ORM\Entity(repositoryClass="App\Repository\AccommodationTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DocumentObjectType
{
    use IdentifiableTrait
    ;
    use TimestampableTrait
    ;
    use SluggableNameTrait
    ;

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
}
