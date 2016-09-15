<?php

namespace Drupal\ldap_authentication;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modifies the user.auth service.
 */
class LdapAuthenticationServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('user.auth');
    $definition->setClass('Drupal\ldap_authentication\LdapAuth');
    $definition->addArgument(new Reference(('ldap.client')));
  }

}
