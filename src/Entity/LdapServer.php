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
  protected $ssl;

  /**
   * Use StartTLS for connection.
   *
   * @var boolean
   */
  protected $start_tls;

  /**
   * Follow LDAP Referrals .
   *
   * @var boolean
   */
  protected $referrals;

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
   * The Base DN.
   *
   * @var string
   */
  protected $base_dn;

  /**
   * Get LDAP Server status.
   */
  public function isActive() {
    return $this->status;
  }

  /**
   * Get LDAP Server port.
   *
   * @return string
   *   The server host.
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * Get LDAP Server status.
   *
   * @return bool
   *   The server status.
   */
  public function getPort() {
    return $this->port;
  }

  /**
   * Use SSL for connection.
   *
   * @return bool
   *   The use ssl indicator.
   */
  public function getSsl() {
    return $this->ssl;
  }

  /**
   * Use StartTLS for connection.
   *
   * @return bool
   *   The use start tls indicator.
   */
  public function getStartTls() {
    return $this->start_tls;
  }

  /**
   * Get the bind username.
   *
   * @return string
   *   The username.
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Get the bind password.
   *
   * @return string
   *   The bind password.
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * Get the base dn.
   *
   * @return string
   *   The base dn.
   */
  public function getBaseDn() {
    return $this->base_dn;
  }

  /**
   * Follow referrals.
   *
   * @return bool
   *   The follow referrals indicator.
   */
  public function getReferrals() {
    return $this->referrals;
  }

}
