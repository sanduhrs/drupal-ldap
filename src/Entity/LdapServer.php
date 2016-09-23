<?php

namespace Drupal\ldap\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the LDAP Server entity.
 *
 * @ConfigEntityType(
 *   id = "ldap_server",
 *   label = @Translation("LDAP Servers"),
 *   handlers = {
 *     "list_builder" = "Drupal\ldap\LdapServerListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ldap\Form\LdapServerForm",
 *       "edit" = "Drupal\ldap\Form\LdapServerForm",
 *       "delete" = "Drupal\ldap\Form\LdapServerDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ldap\LdapServerHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ldap_server",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/people/ldap/server/{ldap_server}",
 *     "add-form" = "/admin/config/people/ldap/server/add",
 *     "edit-form" = "/admin/config/people/ldap/server/{ldap_server}/edit",
 *     "delete-form" = "/admin/config/people/ldap/server/{ldap_server}/delete",
 *     "collection" = "/admin/config/people/ldap/server"
 *   }
 * )
 */
class LdapServer extends ConfigEntityBase implements LdapServerInterface {

  /**
   * The LDAP Server ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The LDAP Server label.
   *
   * @var string
   */
  protected $label;

  /**
   * The LDAP Server host.
   *
   * @var string
   */
  protected $host;

  /**
   * The LDAP Server port.
   *
   * @var integer
   */
  protected $port;

  /**
   * Use SSL for connection.
   *
   * @var boolean
   */
  protected $use_ssl;

  /**
   * The Bind Username.
   *
   * @var string
   */
  protected $username;

  /**
   * The Bind password.
   *
   * @var string
   */
  protected $password;

  /**
   * Bind requires DN.
   *
   * @var boolean
   */
  protected $bind_requires_dn;

  /**
   * The Base DN.
   *
   * @var string
   */
  protected $base_dn;

  /**
   * Account Name Canonicalization.
   *
   * @var integer
   */
  protected $account_canonical_form;

  /**
   * The Account Domain Name.
   *
   * @var string
   */
  protected $account_domain_name;

  /**
   * The Short Account Domain Name.
   *
   * @var string
   */
  protected $account_domain_name_short;

  /**
   * The Account Filter Format.
   *
   * @var string
   */
  protected $account_filter_format;

  /**
   * Allow empty password.
   *
   * @var boolean
   */
  protected $allow_empty_password;

  /**
   * Use StartTLS for connection.
   *
   * @var boolean
   */
  protected $use_start_tls;

  /**
   * Follow LDAP Referrals.
   *
   * @var boolean
   */
  protected $opt_referrals;

  /**
   * Try username split.
   *
   * @var boolean
   */
  protected $try_username_split;

  /**
   * The Network Timeout.
   *
   * @var integer
   */
  protected $network_timeout;

  /**
   * @return boolean
   */
  public function isActive() {
    return $this->status;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @return string
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * @return int
   */
  public function getPort() {
    return $this->port;
  }

  /**
   * @return boolean
   */
  public function useSsl() {
    return $this->use_ssl;
  }

  /**
   * @return string
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * @return string
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @return boolean
   */
  public function bindRequiresDn() {
    return $this->bind_requires_dn;
  }

  /**
   * @return string
   */
  public function getBaseDn() {
    return $this->base_dn;
  }

  /**
   * @return int
   */
  public function getAccountCanonicalForm() {
    return $this->account_canonical_form;
  }

  /**
   * @return string
   */
  public function getAccountDomainName() {
    return $this->account_domain_name;
  }

  /**
   * @return string
   */
  public function getAccountDomainNameShort() {
    return $this->account_domain_name_short;
  }

  /**
   * @return string
   */
  public function getAccountFilterFormat() {
    return $this->account_filter_format;
  }

  /**
   * @return boolean
   */
  public function allowEmptyPassword() {
    return $this->allow_empty_password;
  }

  /**
   * @return boolean
   */
  public function useStartTls() {
    return $this->use_start_tls;
  }

  /**
   * @return boolean
   */
  public function useOptReferrals() {
    return $this->opt_referrals;
  }

  /**
   * @return boolean
   */
  public function tryUsernameSplit() {
    return $this->try_username_split;
  }

  /**
   * @return int
   */
  public function getNetworkTimeout() {
    return $this->network_timeout;
  }

}
