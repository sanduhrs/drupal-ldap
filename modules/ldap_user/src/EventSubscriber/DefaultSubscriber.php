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
  static function getSubscribedEvents() {
    $events['entity_type.definition.create'] = ['entity_type_definition_create'];
    $events['entity_type.definition.update'] = ['entity_type_definition_update'];

    return $events;
  }

  /**
   * This method is called whenever the entity_type.definition.create event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function entity_type_definition_create(Event $event) {
    drupal_set_message('Event entity_type.definition.create thrown by Subscriber in module ldap_user.', 'status', TRUE);
  }
  /**
   * This method is called whenever the entity_type.definition.update event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function entity_type_definition_update(Event $event) {
    drupal_set_message('Event entity_type.definition.update thrown by Subscriber in module ldap_user.', 'status', TRUE);
  }

}
