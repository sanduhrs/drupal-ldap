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
      'host' => $server->getHost(),
      'port' => $server->getPort(),
      'useSsl' => $server->useSsl(),
      'username' => $server->getUsername(),
      'password' => $server->getPassword(),
      'bindRequiresDn' => $server->bindRequiresDn(),
      'baseDn' => $server->getBaseDn(),
      'accountCanonicalForm' => $server->getAccountCanonicalForm(),
      'accountDomainName' => $server->getAccountDomainName(),
      'accountDomainNameShort' => $server->getAccountDomainNameShort(),
      'accountFilterFormat' => $server->getAccountFilterFormat(),
      'allowEmptyPassword' => $server->allowEmptyPassword(),
      'useStartTls' => $server->useStartTls(),
      'optReferrals' => $server->useOptReferrals(),
      'tryUsernameSplit' => $server->tryUsernameSplit(),
      'networkTimeout' => $server->getNetworkTimeout(),
    ]);
    return $this;
  }

}
