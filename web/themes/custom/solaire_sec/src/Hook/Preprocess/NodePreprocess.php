<?php

namespace Drupal\solaire_sec\Hook\Preprocess;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for solaire_sec.
 */
class NodePreprocess {
  /**
   * Load a node by its ID.
   *
   * @param int $node_id
   *   The node ID.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The loaded node, or NULL when no node exists for the ID.
   */
  public function loadNodeById(int $node_id): ?NodeInterface {
    $node = Node::load($node_id);

    return $node instanceof NodeInterface ? $node : NULL;
  }

  /**
   * @file
   * Functions to support theming.
   */

  /**
   * Implements hook_preprocess_node().
   */
  #[Hook('preprocess_node')]
  public function preprocess(array &$variables): void {
    $node = $variables['node'] ?? NULL;

    if (!$node instanceof NodeInterface) {
      return;
    }

    if (!empty($variables['view_mode']) && $variables['view_mode'] == 'card') {
      $variables['card'] = $this->preprocessNodeOffers($node, $variables);
    }
  }

  /**
   * Offers preprocess
   */
  public function preprocessNodeOffers($node, $variables) {

    // view mode card.
    if (!empty($variables['elements']['#view_mode']) &&
      $variables['elements']['#view_mode'] === 'card'
    ) {
      return $this->nodeCardView($node);
    }

  }

  /**
   * View mode Card
   */
  public function nodeCardView($node) {
    $bannerImage = NULL;
    if ($node->hasField('field_mobile_thumbnail') && !$node->get('field_mobile_thumbnail')->isEmpty()) {
      $bannerImage = $node->get('field_mobile_thumbnail')->view('card');
    }

    $teaserSummary = NULL;
    if ($node->hasField('field_teaser_summary') && !$node->get('field_teaser_summary')->isEmpty()) {
      $teaserSummary = $node->get('field_teaser_summary')->view('card');
    }

    return [
      'image' => $bannerImage,
      'body' => $teaserSummary,
    ];
  }
}
