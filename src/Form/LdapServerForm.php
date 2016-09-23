<?php

namespace Drupal\ldap\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Zend\Ldap\Ldap;

/**
 * Class LdapServerForm.
 *
 * @package Drupal\ldap\Form
 */
class LdapServerForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\ldap\Entity\LdapServer $ldap_server */
    $ldap_server = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ldap_server->label(),
      '#description' => $this->t("Label for the LDAP Server."),
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $ldap_server->id(),
      '#disabled' => !$ldap_server->isNew(),
      '#maxlength' => 64,
      '#description' => $this->t('A unique name for this item. It must only contain lowercase letters, numbers, and underscores.'),
      '#machine_name' => array(
        'exists' => '\Drupal\ldap\Entity\LdapServer::load',
      ),
    );

    $form['server'] = array(
      '#type' => 'fieldset',
      '#title' => t('LDAP Server Connection Information'),
    );
    $form['server']['host'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Host'),
      '#default_value' => $ldap_server->getHost(),
      '#required' => TRUE,
    );
    $form['server']['port'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Port'),
      '#maxlength' => 5,
      '#default_value' => $ldap_server->getPort() ? $ldap_server->getPort() : '389',
      '#required' => TRUE,
    );
    $form['server']['status'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $ldap_server->isActive(),
    );
    $form['server']['use_ssl'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use SSL'),
      '#default_value' => $ldap_server->useSsl(),
    );
    $form['server']['use_start_tls'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use StartTLS'),
      '#default_value' => $ldap_server->useStartTls(),
    );
    $form['server']['bind_requires_dn'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Bind requires DN'),
      '#default_value' => $ldap_server->bindRequiresDn(),
    );
    $form['server']['opt_referrals'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Follow LDAP Referrals'),
      '#default_value' => $ldap_server->useOptReferrals(),
    );
    $form['server']['try_username_split'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Try Username Split'),
      '#default_value' => $ldap_server->tryUsernameSplit(),
    );
    $form['server']['account_canonical_form'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Account Canonical Form'),
      '#default_value' => $ldap_server->getAccountCanonicalForm(),
      '#options' => [
        Ldap::ACCTNAME_FORM_DN => 'CN=Alice Baker,CN=Users,DC=example,DC=com',
        Ldap::ACCTNAME_FORM_USERNAME => 'abaker',
        Ldap::ACCTNAME_FORM_BACKSLASH => 'EXAMPLE\abaker',
        Ldap::ACCTNAME_FORM_PRINCIPAL => 'abaker@example.com',
      ],
    );
    $form['server']['account_domain_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Account Domain Name'),
      '#default_value' => $ldap_server->getAccountDomainName(),
    );
    $form['server']['account_domain_name_short'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Account Domain Name Short'),
      '#default_value' => $ldap_server->getAccountDomainNameShort(),
    );
    $form['server']['account_filter_format'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Account Filter Format'),
      '#default_value' => $ldap_server->getAccountFilterFormat(),
    );
    $form['server']['allow_empty_password'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Allow Empty Password'),
      '#default_value' => $ldap_server->allowEmptyPassword(),
    );
    $form['server']['network_timeout'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Network Timeout'),
      '#default_value' => $ldap_server->getNetworkTimeout(),
    );

    $form['directory'] = array(
      '#type' => 'fieldset',
      '#title' => t('Directory Information'),
    );
    $form['directory']['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $ldap_server->getUsername(),
    );
    $form['directory']['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $ldap_server->getPassword(),
    );
    $form['directory']['base_dn'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Base DN'),
      '#default_value' => $ldap_server->getBaseDn(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $ldap_server = $this->entity;

    // Keep the old password if no new password has been provided by the user.
    $new_password = $form_state->getValue('password');
    $old_password = $form['directory']['password']['#default_value'];
    if (empty($new_password)) {
      $ldap_server->set('password', $old_password);
    }

    $status = $ldap_server->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label LDAP Server.', [
          '%label' => $ldap_server->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label LDAP Server.', [
          '%label' => $ldap_server->label(),
        ]));
    }
    $form_state->setRedirectUrl($ldap_server->urlInfo('collection'));
  }

}
