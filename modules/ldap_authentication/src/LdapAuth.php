<?php

namespace Drupal\ldap_authentication;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Password\PasswordInterface;
use Drupal\ldap\LdapClientInterface;
use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;

/**
 * Validates user authentication credentials.
 */
class LdapAuth implements UserAuthInterface {

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

    $this->username = $username;
    $this->password = $password;

    if (!empty($username) && strlen($password) > 0) {
      /** @var \Drupal\ldap_server\Entity\LdapServer $server */
      $server = entity_load('ldap_server', 'openldap');

      // Check for LDAP account.
      $this->ldapClient->setServer($server);
      try {
        // Bind LDAP account.
        $this->ldapClient->bind(
          'cn=' . $username . ',ou=People,dc=erdfisch,dc=de',
          $password
        );
        // Fetch LDAP account.
        $this->ldapAccount = $this->ldapClient
          ->getEntry('cn=' . $username . ',ou=people,dc=erdfisch,dc=de');
        $this->mail = reset($this->ldapAccount['mail']);
      }
      catch (\Exception $e) {
        // Invalid credentials or account does not exist.
        return FALSE;
      }

      // Check for local account.
      $account_search = $this->entityManager->getStorage('user')
        ->loadByProperties(array('name' => $username));
      if ($account = reset($account_search)) {
        $uid = $account->id();

        if ($this->passwordChecker->check($password, $account->getPassword())) {
          // Successful authentication.
          $uid = $account->id();

          // Update user to new password scheme if needed.
          if ($this->passwordChecker->needsRehash($account->getPassword())) {
            $account->setPassword($password);
            $account->save();
          }
        }
      }

      // Create local account.
      if (!$account) {
        $uid = $this->createUser();
      }
    }

    return $uid;
  }

  /**
   * Create a user.
   *
   * @return int
   *   The user id.
   */
  private function createUser() {
    $user = User::create([
      'name' => $this->username,
      'pass' => $this->password,
      'mail' => $this->mail,
      'init' => $this->mail,
    ]);
    $user->enforceIsNew()->activate()->save();
    return $user->id();
  }

}
