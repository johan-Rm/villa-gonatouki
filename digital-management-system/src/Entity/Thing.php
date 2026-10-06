<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * The most generic kind of creative work, including books, movies, photographs, software programs, etc.
 *
 * @see http://schema.org/CreativeWork Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/CreativeWork",
 *     collectionOperations={"get"={"method"="GET"}},
 *     itemOperations={"get"={"method"="GET"}}
 *  )
 */
class Thing
{
    use IdentifiableTrait
        ;
    use ThingTrait
    ;
}
