<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class SecurityController extends AbstractController
{
    protected function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    protected function persistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }

    protected function updateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }
}
