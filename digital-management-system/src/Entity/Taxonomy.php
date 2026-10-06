<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 * @ORM\HasLifecycleCallbacks()
 */
class Taxonomy
{
    use IdentifiableTrait
        // , ThingTrait
        ;
    use SluggableNameTrait
        ;
    use TimestampableTrait
        ;
    use AdministrableTrait
    ;
}
