<?php

namespace Drupal\ldap_server\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LdapServerForm.
 *
 * @package Drupal\ldap_server\Form
 */
class LdapServerForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\ldap_server\Entity\LdapServer $ldap_server */
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
        'exists' => '\Drupal\ldap_server\Entity\LdapServer::load',
      ),
    );
    $form['host'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Host'),
      '#default_value' => $ldap_server->getHost(),
      '#required' => TRUE,
    );
    $form['port'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Port'),
      '#maxlength' => 5,
      '#default_value' => $ldap_server->getPort() ? $ldap_server->getPort() : '389',
      '#required' => TRUE,
    );
    $form['status'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $ldap_server->isActive(),
    );
    $form['ssl'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use SSL'),
      '#default_value' => $ldap_server->getSsl(),
    );
    $form['start_tls'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use StartTLS'),
      '#default_value' => $ldap_server->getStartTls(),
    );
    $form['referrals'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Follow LDAP Referrals '),
      '#default_value' => $ldap_server->getReferrals(),
    );

    $form['bind'] = array(
      '#type' => 'fieldset',
      '#title' => t('Bind'),
    );
    $form['bind']['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $ldap_server->getUsername(),
    );
    $form['bind']['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $ldap_server->getPassword(),
    );
    $form['bind']['base_dn'] = array(
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
    $old_password = $form['bind']['password']['#default_value'];
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
