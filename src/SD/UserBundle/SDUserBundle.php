<?php

namespace SD\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SDUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
