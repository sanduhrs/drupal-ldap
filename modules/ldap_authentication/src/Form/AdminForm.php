<?php

namespace Drupal\ldap_authentication\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdminForm.
 *
 * @package Drupal\ldap_authentication\Form
 */
class AdminForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ldap_authentication_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General authentication settings'),
    ];
    $form['general']['mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#default_value' => 1,
      '#options' => [
        1 => 'Mixed Drupal LDAP',
        2 => 'Mostly LDAP',
        3 => 'Stricly LDAP',
      ],
      '#description' => 'Choose the authentication mode. Modes: <ul><li><em>Mixed Drupal LDAP</em> mode: Try local authentication first, fallback on LDAP.</li><li><em>Mostly LDAP</em> mode: Use LDAP for authentication, exclude user 1</li><li><em>Stricly LDAP</em> mode: Just use LDAP, including user 1.</li></ul>',
    ];
    $form['general']['server'] = [
      '#type' => 'fieldset'
    ];
    $form['general']['server']['attributes']['base_dn'] = [
      '#title' => $this->t('Base DN'),
      '#type' => 'textarea'
    ];
    $form['general']['server']['attributes']['auth_name'] = [
      '#title' => $this->t('AuthName Attribute'),
      '#type' => 'textfield'
    ];
    $form['general']['server']['attributes']['account'] = [
      '#title' => $this->t('AccountName Attribute'),
      '#type' => 'textfield'
    ];
    $form['general']['server_one']['attributes']['mail'] = [
      '#title' => $this->t('Mail Attribute'),
      '#type' => 'textfield'
    ];
    $form['general']['server']['attributes']['thumbnail'] = [
      '#title' => $this->t('Thumbnail Attribute'),
      '#type' => 'textfield'
    ];
    $form['general']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['server'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Per server authentication settings'),
    ];

    $form['server']['server_one'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Server One'),
    ];
    $form['server']['server_one']['attributes']['base_dn'] = [
      '#title' => $this->t('Base DN'),
      '#type' => 'textarea'
    ];
    $form['server']['server_one']['attributes']['auth_name'] = [
      '#title' => $this->t('AuthName Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_one']['attributes']['account'] = [
      '#title' => $this->t('AccountName Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_one']['attributes']['mail'] = [
      '#title' => $this->t('Mail Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_one']['attributes']['thumbnail'] = [
      '#title' => $this->t('Thumbnail Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_one']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['server']['server_two'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Server Two'),
    ];
    $form['server']['server_two']['attributes']['base_dn'] = [
      '#title' => $this->t('Base DN'),
      '#type' => 'textarea'
    ];
    $form['server']['server_two']['attributes']['auth_name'] = [
      '#title' => $this->t('AuthName Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_two']['attributes']['account'] = [
      '#title' => $this->t('AccountName Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_two']['attributes']['mail'] = [
      '#title' => $this->t('Mail Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_two']['attributes']['thumbnail'] = [
      '#title' => $this->t('Thumbnail Attribute'),
      '#type' => 'textfield'
    ];
    $form['server']['server_two']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
