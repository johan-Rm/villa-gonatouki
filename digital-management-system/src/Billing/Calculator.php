<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Billing;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManager;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class Calculator
{
    /** @var ConfigManager */
    private $config;
    private $em;
    private $token;
    private $translator;

    public function __construct(EntityManager $em, ConfigManager $config, TokenStorage $token, TranslatorInterface $translator)
    {
        $this->config = $config;
        $this->em = $em;
        $this->token = $token;
        $this->translator = $translator;
    }

    public function totalWithoutTaxes(Invoice $invoice)
    {
//        $user = $this->token->getToken()->getUser();
        $totalWithoutTaxes = null;
        foreach ($invoice->getElements() as $element) {
            $totalWithoutTaxes += ($element->getQuantity() * $element->getUnitPrice());
        }
        $invoice->setTotalWithoutTaxes($totalWithoutTaxes);

        return $invoice;
    }

    public function totalPaymentDue(Invoice $invoice)
    {
//        $user = $this->token->getToken()->getUser();
        $total = $invoice->getTotalWithoutTaxes();
        $totalPaymentDue = $total + (($total * $invoice::TVA) / 100);

        $invoice->setTotalPaymentDue($totalPaymentDue);

        return $invoice;
    }
}
