<?php

namespace Drupal\client_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'ClientLinks' block.
 *
 * @Block(
 *  id = "client_links",
 *  admin_label = @Translation("Client links"),
 * )
 */
class ClientLinks extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxy $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $uid = \Drupal::currentUser()->id();
    $build['client_links'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#title' => 'Client links',
      '#attributes' => ['class' => 'tabs--primary nav nav-tabs'],
      '#wrapper_attributes' => ['class' => 'container'],
      '#cache' => ['max-age' => 0],
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('My services'),
      '#type' => 'link',
      '#url' => Url::fromRoute('view.order_items.page_1', ['user' => $uid]),
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('Invoices'),
      '#type' => 'link',
      '#url' => Url::fromRoute('view.commerce_user_orders.order_page', ['user' => $uid]),
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('Tickets'),
      '#type' => 'link',
      '#url' => Url::fromRoute('view.tickets.page_1', ['user' => $uid]),
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('Open ticket'),
      '#type' => 'link',
      '#url' => Url::fromRoute('node.add', ['node_type' => 'ticket']),
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('Billing information'),
      '#type' => 'link',
      //'#url' => Url::fromRoute('entity.user.canonical', ['user' => $uid]),
      '#url' => Url::fromRoute('entity.profile.type.user_profile_form', ['user' => $uid, 'profile_type' => 'customer']),
    ];
    $build['client_links']['#items'][] = [
      '#title' => $this->t('View/edit account'),
      // '#theme' => 'profileee',.
      '#type' => 'link',
      '#url' => Url::fromRoute('entity.user.edit_form', ['user' => $uid]),
    ];

    return $build;
  }

}
