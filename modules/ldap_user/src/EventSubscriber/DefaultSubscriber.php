<?php

namespace Drupal\ldap_user\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DefaultSubscriber.
 *
 * @package Drupal\ldap_user
 */
class DefaultSubscriber implements EventSubscriberInterface {

  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['entity_type.definition.create'] = ['entity_type_definition_create'];
    $events['entity_type.definition.update'] = ['entity_type_definition_update'];

    return $events;
  }

  /**
   * Called whenever the entity_type.definition.create event is dispatched.
   *
   * @param Event $event
   *   The event.
   */
  public function entity_type_definition_create(Event $event) {
    drupal_set_message('Event entity_type.definition.create thrown by Subscriber in module ldap_user.', 'status', TRUE);
  }

  /**
   * Called whenever the entity_type.definition.update event is dispatched.
   *
   * @param Event $event
   *   The event.
   */
  public function entity_type_definition_update(Event $event) {
    drupal_set_message('Event entity_type.definition.update thrown by Subscriber in module ldap_user.', 'status', TRUE);
  }

}
