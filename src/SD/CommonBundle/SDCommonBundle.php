<?php

namespace SD\CommonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * SDCommonBundle
 */
class SDCommonBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'ITDoorsCommonBundle';
    }
}
