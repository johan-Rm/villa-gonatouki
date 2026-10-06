<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class PaymentMethodRepository extends EntityRepository
{
    public static function getPaymentMethod(EntityRepository $er, EntityManager $em, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $query = $er->createQueryBuilder('payment_method');

        return $query;
    }
}
