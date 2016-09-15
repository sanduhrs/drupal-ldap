<?php

namespace Drupal\ldap_authentication;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\ldap\LdapClientInterface;
use Drupal\user\UserAuthInterface;

/**
 * Validates user authentication credentials.
 */
class LdapAuth implements UserAuthInterface {

  // Strictly authenticate with LDAP only.
  const AUTH_TYPE_STRICT = 0;

  // Authenticate with LDAP first, the try Drupal.
  const AUTH_TYPE_MIXED = 1;

  // Authenticate with Drupal first, then try LDAP.
  const AUTH_TYPE_FALLBACK = 2;

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
   * @var \Drupal\ldap\LdapClientInterface;
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
   * Constructs a LdapAuth object.
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
    $uid = FALSE;

    /** @var \Drupal\externalauth\ExternalAuth $externalauth */
    $externalauth = $authmap = \Drupal::service('externalauth.externalauth');
    $settings = \Drupal::config('ldap_authentication.settings');

    $this->username = $username;
    $this->password = $password;

    if (!empty($username) && strlen($password) > 0) {
      $servers = \Drupal::entityTypeManager()->getStorage('ldap_server')
        ->loadMultiple();

      /** @var \Drupal\ldap\Entity\LdapServer $server */
      foreach ($servers as $server) {
        $this->ldapClient->setOptions([
          'host' => $server->getHost(),
          'port' => $server->getPort(),
          'useSsl' => $server->getSsl(),
          'username' => $server->getUsername(),
          'password' => $server->getPassword(),
          'bindRequiresDn' => TRUE,
          'baseDn' => $server->getBaseDn(),
          'useStartTls' => $server->getStartTls(),
          'optReferrals' => TRUE,
        ]);
        try {
          // Check if we can bind the user with given credentials.
          $query = $settings->get('auth_name') . '=' . $username . ',' . $settings->get('base_dn');
          $this->ldapClient->bind($query, $password);

          // Get the personal data.
          if ($person = $this->ldapClient->getEntry($query)) {
            if (isset($person[$settings->get('mail')])) {
              $this->mail = reset($person[$settings->get('mail')]);
            }
            if (isset($person[$settings->get('thumbnail')])) {
              $this->picture = reset($person[$settings->get('thumbnail')]);
            }
          }
        }
        catch (\Exception $e) {
          return $uid;
        }

        // Check for local account.
        $account = $externalauth->load($query, 'ldap');
        if ($account) {
          $account = $externalauth->login($query, 'ldap');
          return $account->id();
        }

        // Create local account.
        if (!$account) {
          $account = $externalauth->loginRegister($query, 'ldap');
          $account->setUsername($username);
          $account->setEmail($this->mail);
          $account->save();
          return $account->id();
        }
      }
    }

    return $uid;
  }

}
