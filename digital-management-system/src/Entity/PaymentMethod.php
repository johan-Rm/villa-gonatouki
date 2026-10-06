<?php

namespace App\Entity;

use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentMethod.
 *
 * @ORM\Table(name="payment_method")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMethodRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PaymentMethod
{
    use IdentifiableTrait
        // , ThingTrait
        ;
    use TimestampableTrait
        ;
    use SluggableNameTrait;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="paymentMethod")
     */
    private $invoices;

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

    /**
     * @return mixed
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @param mixed $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }
}
