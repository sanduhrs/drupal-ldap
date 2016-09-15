<?php

namespace Drupal\ldap;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of LDAP Servers entities.
 */
class LdapServerListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('LDAP Server');
    $header['id'] = $this->t('Machine name');
    $header['status'] = $this->t('Enabled');
    $header['ssl'] = $this->t('SSL');
    $header['starttls'] = $this->t('STARTTLS');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['status'] = $entity->isActive() ? '✓' : '✕';
    $row['ssl'] = $entity->getSsl() ? '✓' : '✕';
    $row['starttls'] = $entity->getStartTls() ? '✓' : '✕';
    return $row + parent::buildRow($entity);
  }

}
