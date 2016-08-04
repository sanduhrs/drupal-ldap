<?php

namespace Drupal\ldap_server\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining LDAP Server entities.
 */
interface LdapServerInterface extends ConfigEntityInterface {

  public function isActive();

  public function getPort();

  public function getHost();

  public function getSsl();

  public function getStartTls();

  public function getUsername();

  public function getPassword();

  public function getBaseDn();

  public function getReferrals();

}

