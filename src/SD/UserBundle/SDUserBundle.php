<?php

namespace SD\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * SDUserBundle
 */
class SDUserBundle extends Bundle
{

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
