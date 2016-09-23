<?php

namespace Drupal\ldap_authentication\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ldap_authentication\LdapAuthentication;

/**
 * Class SettingsForm.
 *
 * @package Drupal\ldap_authentication\Form
 */
class SettingsForm extends ConfigFormBase implements ContainerInjectionInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ldap_authentication_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ldap_authentication.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->config('ldap_authentication.settings');
    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General authentication settings'),
    ];
    $form['general']['mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#options' => [
        LdapAuthentication::AUTH_TYPE_MIXED => $this->t('LDAP authentication with local fallback'),
        LdapAuthentication::AUTH_TYPE_STRICT => $this->t('Strictly LDAP authentication'),
      ],
      '#default_value' => $settings->get('mode'),
      '#description' => 'Choose the authentication mode.',
    ];
    $form['general']['server']['attributes']['base_dn'] = [
      '#title' => $this->t('Base DN'),
      '#type' => 'textfield',
      '#placeholder' => $this->t('ou=people,dc=example,dc=org'),
      '#default_value' => $settings->get('base_dn'),
    ];
    $form['general']['server']['attributes']['auth_name'] = [
      '#title' => $this->t('Auth name Attribute'),
      '#type' => 'textfield',
      '#placeholder' => $this->t('cn'),
      '#default_value' => $settings->get('auth_name'),
    ];
    $form['general']['server']['attributes']['account'] = [
      '#title' => $this->t('Account name Attribute'),
      '#type' => 'textfield',
      '#placeholder' => $this->t('uid'),
      '#default_value' => $settings->get('account'),
    ];
    $form['general']['server']['attributes']['mail'] = [
      '#title' => $this->t('Mail Attribute'),
      '#type' => 'textfield',
      '#placeholder' => $this->t('mail'),
      '#default_value' => $settings->get('mail'),
    ];
    $form['general']['server']['attributes']['thumbnail'] = [
      '#title' => $this->t('Thumbnail Attribute'),
      '#type' => 'textfield',
      '#placeholder' => $this->t('thumbnail'),
      '#default_value' => $settings->get('thumbnail'),
    ];
    $form['general']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /** @var \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $settings */
    $settings = $this->config('ldap_authentication.settings');
    $settings
      ->set('mode', $form_state->getValue('mode'))
      ->set('base_dn', $form_state->getValue('base_dn'))
      ->set('auth_name', $form_state->getValue('auth_name'))
      ->set('account', $form_state->getValue('account'))
      ->set('mail', $form_state->getValue('mail'))
      ->set('thumbnail', $form_state->getValue('thumbnail'))
      ->save();
  }

}
