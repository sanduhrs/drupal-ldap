<?php

namespace Drupal\ldap_authentication;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\ldap\LdapClientInterface;
use Drupal\user\UserAuthInterface;
use Zend\Ldap\Ldap;
use Zend\Ldap\Dn;

/**
 * Class LdapAuthentication.
 *
 * @package Drupal\ldap_authentication
 */
class LdapAuthentication implements UserAuthInterface {

  // Strictly authenticate with LDAP only.
  const AUTH_TYPE_STRICT = 0;

  // Authenticate with LDAP first, the try Drupal.
  const AUTH_TYPE_MIXED = 1;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The password hashing service.
   *
   * @var \Drupal\Core\Password\PasswordInterface
   */
  protected $passwordChecker;

  /**
   * The ldap client service.
   *
   * @var \Drupal\ldap\LdapClient.
   */
  protected $ldapClient;

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The LDAP account entry.
   *
   * @var string
   */
  protected $ldapAccount;

  /**
   * The username.
   *
   * @var string
   */
  protected $username;

  /**
   * The password.
   *
   * @var string
   */
  protected $password;

  /**
   * The mail.
   *
   * @var string
   */
  protected $mail;

  /**
   * The user picture.
   *
   * @var string
   */
  protected $picture;

  /**
   * Constructs a LdapAuthentication object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Password\PasswordInterface $password_checker
   *   The password service.
   * @param \Drupal\ldap\LdapClientInterface $ldap_client
   *   The ldap client service.
   */
  public function __construct(
      EntityManagerInterface $entity_manager,
      PasswordInterface $password_checker,
      LdapClientInterface $ldap_client
  ) {
    $this->entityManager = $entity_manager;
    $this->passwordChecker = $password_checker;
    $this->ldapClient = $ldap_client;
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate($username, $password) {
    /** @var \Drupal\externalauth\ExternalAuth $externalauth */
    $externalauth = $authmap = \Drupal::service('externalauth.externalauth');
    $settings = \Drupal::config('ldap_authentication.settings');

    $this->username = $username;
    $this->password = $password;

    if (!empty($username) && strlen($password) > 0) {
      $servers = \Drupal::entityTypeManager()->getStorage('ldap_server')
        ->loadByProperties(['status' => 1]);
      $server = reset($servers);
      $this->ldapClient->setServer($server);

      $canonical = Dn::escapeValue($username);
      if ($auth_name = $settings->get('auth_name')) {
        $canonical = $this->ldapClient->getCanonicalAccountName(
          $auth_name . '=' . $canonical . ',' . $settings->get('base_dn'),
          Ldap::ACCTNAME_FORM_DN
        );
      }

      // Check if we can bind the user with given credentials.
      try {
        $this->ldapClient->bind($canonical, $password);
      }
      catch(\Exception $e) {
        // Check for authentication mode.
        $config = \Drupal::config('ldap_authentication.settings');
        if ($config->get('mode') === static::AUTH_TYPE_STRICT) {
          // Stop authenticating when mode is strict.
          return FALSE;
        }

        // Check for local account without ldap connection.
        $account_search = \Drupal::entityTypeManager()->getStorage('user')
          ->loadByProperties(['name' => $username]);
        // Mostly taken from UserAuth::authenticate().
        if ($account = reset($account_search)) {
          if ($this->passwordChecker->check($password,
            $account->getPassword())
          ) {
            // Update user to new password scheme if needed.
            if ($this->passwordChecker->needsRehash($account->getPassword())) {
              $account->setPassword($password);
              $account->save();
            }
            return $account->id();
          }
        }
        return FALSE;
      }

      // Bind was successful, get the personal data.
      if ($person = $this->ldapClient->getEntry($canonical)) {
        if (isset($person[$settings->get('mail')])) {
          $this->mail = reset($person[$settings->get('mail')]);
        }
        // TODO: Check for thumbnail and store local copy
        //if (isset($person[$settings->get('thumbnail')])) {
        //  $this->picture = reset($person[$settings->get('thumbnail')]);
        //}
      }

      // Check for local account with ldap connection.
      $account = $externalauth->load($canonical, 'ldap');
      if ($account) {
        $account = $externalauth->login($canonical, 'ldap');
        return $account->id();
      }

      // Create local account.
      if (!$account) {
        $account = $externalauth->loginRegister($canonical, 'ldap');
        $account->setUsername($username);
        $account->setEmail($this->mail);
        $account->save();
        return $account->id();
      }
    }

    return FALSE;
  }

}
