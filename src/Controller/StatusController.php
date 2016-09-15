<?php

namespace Drupal\ldap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RedirectController.
 *
 * @package Drupal\ldap\Controller
 */
class StatusController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Toggle the active state.
   *
   * @param mixed $id
   *    The id of the entity given by route url.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response object that may be returned by the controller.
   */
  public function toggle($id) {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->entityTypeManager
      ->getStorage('ldap_server');

    /** @var \Drupal\ldap\Entity\LdapServer $server */
    $server = $storage->load($id);
    $server->setStatus(!$server->status());
    $server->save();

    drupal_set_message($this->t(
      'The LDAP server <a href=":url">@server</a> has been %status.',
      [
        ':url' => Url::fromRoute(
          'entity.ldap_server.edit_form',
          [
            'ldap_server' => $server->id(),
          ]
        )->toString(),
        '@client' => $server->label(),
        '%status' => $server->status() ? 'enabled' : 'disabled',
      ]
    ));
    return $this->redirect("entity.ldap_server.collection");
  }

}
