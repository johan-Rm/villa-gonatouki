<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
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
 *
 * @ ORM\Entity(repositoryClass="App\Repository\CustomerCardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CustomerCard
{
    use IdentifiableTrait
        ;
    use TimestampableTrait
        ;
    use AdministrableTrait
    ;

    /**
     * @var Person|null The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     */
    private $person;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setPerson(?Person $person): void
    {
        $this->person = $person;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }
}
