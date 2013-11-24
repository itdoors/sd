<?php

namespace SD\UserBundle\Service;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class SDPasswordEncoder implements PasswordEncoderInterface
{

  private $algorithm;

  /**
   * Constructor.
   *
   * @param string  $algorithm          The digest algorithm to use
   */
  public function __construct($algorithm = 'sha1')
  {
    $this->algorithm = $algorithm;
  }

  /**
   * {@inheritdoc}
   */
  public function encodePassword($raw, $salt)
  {
    return call_user_func_array($this->algorithm, array($salt.$raw));
  }

  public function isPasswordValid($encoded, $raw, $salt)
  {
    return $encoded === $this->encodePassword($raw, $salt);
  }

}