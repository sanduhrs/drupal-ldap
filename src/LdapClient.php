<?php

namespace Drupal\ldap;

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
   * @param \Drupal\ldap_server\Entity\LdapServer $server
   *   The ldap client service.
   * @return \Drupal\ldap\LdapClient
   *   Return current object for chaining.
   */
  public function setServer($server) {
    $this->setOptions([
      'host'                   => $server->getHost(),
      'port'                   => $server->getPort(),
      'useSsl'                 => $server->getSsl(),
      'username'               => $server->getUsername(),
      'password'               => $server->getPassword(),
      'bindRequiresDn'         => TRUE, // FALSE for Active Directory
      'baseDn'                 => $server->getBaseDn(),
      'accountCanonicalForm'   => 1,
      'accountDomainName'      => null,
      'accountDomainNameShort' => null,
      'accountFilterFormat'    => null,
      'allowEmptyPassword'     => false,
      'useStartTls'            => $server->getStartTls(),
      'optReferrals'           => $server->getReferrals(),
      'tryUsernameSplit'       => true,
      'networkTimeout'         => null,
    ]);
    return $this;
  }

  /**
   * Bind user account.
   *
   * @param string $username
   * @param string $password
   * @return \Drupal\ldap\LdapClient
   *   Return current object for chaining.
   * @throws \Zend\Ldap\Exception\LdapException
   */
  public function bind($username = NULL, $password = NULL) {
    parent::bind($username, $password);
    return $this;
  }

}
