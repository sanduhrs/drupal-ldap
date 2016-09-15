<?php

namespace Drupal\ldap;

use Drupal\ldap\Entity\LdapServer;
use Zend\Ldap\Ldap as ZendLdap;

/**
 * Class LdapService.
 *
 * @package Drupal\ldap
 */
class LdapClient extends ZendLdap implements LdapClientInterface {

  /**
   * Constructor.
   */
  public function __construct($options = []) {
    parent::__construct($options);
  }

  /**
   * Add server configuration.
   *
   * @param \Drupal\ldap\Entity\LdapServer $server
   *   The ldap client service.
   *
   * @return \Drupal\ldap\LdapClient
   *   Return current object for chaining.
   */
  public function setServer(LdapServer $server) {
    $this->setOptions([
      'host'                   => $server->getHost(),
      'port'                   => $server->getPort(),
      'useSsl'                 => $server->getSsl(),
      'username'               => $server->getUsername(),
      'password'               => $server->getPassword(),
      // FALSE for Active Directory.
      'bindRequiresDn'         => TRUE,
      'baseDn'                 => $server->getBaseDn(),
      'accountCanonicalForm'   => 1,
      'accountDomainName'      => NULL,
      'accountDomainNameShort' => NULL,
      'accountFilterFormat'    => NULL,
      'allowEmptyPassword'     => FALSE,
      'useStartTls'            => $server->getStartTls(),
      'optReferrals'           => $server->getReferrals(),
      'tryUsernameSplit'       => TRUE,
      'networkTimeout'         => NULL,
    ]);
    return $this;
  }

  /**
   * Bind user account.
   *
   * @param string $username
   *   The username.
   * @param string $password
   *   The password.
   *
   * @return \Drupal\ldap\LdapClient
   *   Return current object for chaining.
   *
   * @throws \Zend\Ldap\Exception\LdapException
   */
  public function bind($username = NULL, $password = NULL) {
    parent::bind($username, $password);
    return $this;
  }

}
