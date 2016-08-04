<?php

namespace Drupal\ldap;

/**
 * Interface LdapClientInterface.
 *
 * @package Drupal\ldap
 */
interface LdapClientInterface {

  public function setServer($server);

  public function bind($username = NULL, $password = NULL);

  public function search($options);

}
