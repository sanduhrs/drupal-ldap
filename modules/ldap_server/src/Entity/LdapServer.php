<?php

namespace Drupal\ldap_server\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the LDAP Server entity.
 *
 * @ConfigEntityType(
 *   id = "ldap_server",
 *   label = @Translation("LDAP Servers"),
 *   handlers = {
 *     "list_builder" = "Drupal\ldap_server\LdapServerListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ldap_server\Form\LdapServerForm",
 *       "edit" = "Drupal\ldap_server\Form\LdapServerForm",
 *       "delete" = "Drupal\ldap_server\Form\LdapServerDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ldap_server\LdapServerHtmlRouteProvider",
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
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * Get LDAP Server status.
   *
   * @return boolean
   */
  public function getPort() {
    return $this->port;
  }

  /**
   * Use SSL for connection.
   *
   * @return boolean
   */
  public function getSsl() {
    return $this->ssl;
  }

  /**
   * Use StartTLS for connection.
   *
   * @return boolean
   */
  public function getStartTls() {
    return $this->start_tls;
  }

  /**
   * Get the bind username.
   *
   * @return string
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Get the bind password.
   *
   * @return string
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * Get the base dn.
   *
   * @return string
   */
  public function getBaseDn() {
    return $this->base_dn;
  }

  /**
   * Follow referrals.
   *
   * @return bool
   */
  public function getReferrals() {
    return $this->referrals;
  }

}
