<?php
namespace Drupal\solaire_sec\Hook\Suggestion;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class BlockSuggestion {

  /**
   * Entity type manager for loading paragraph entities.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * ParagraphPreprocess constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager'),
    );
  }
  /**
   * Implements hook_theme_suggestions_block_alter().
   */
  #[Hook('theme_suggestions_block_alter')]
  public function blockAlter(array &$suggestions, array $variables): void {
    if (empty($variables['elements']['#id'])) {
      return;
    }

    /** @var \Drupal\block\BlockInterface|null $block */
    $block = $this->entityTypeManager
      ->getStorage('block')
      ->load($variables['elements']['#id']);

    if (!$block) {
      return;
    }

    $region = $block->getRegion();
    $plugin_id = $variables['elements']['#plugin_id'];

    $suggestions[] = 'block__' . $region;
    $suggestions[] = 'block__' . $region . '__plugin_id__' . $plugin_id;
    $suggestions[] = 'block__' . $region . '__id__' . $variables['elements']['#id'];
  }
}
