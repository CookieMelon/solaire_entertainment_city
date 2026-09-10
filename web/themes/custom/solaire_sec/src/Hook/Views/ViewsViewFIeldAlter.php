<?php

namespace Drupal\solaire_sec\Hook\Views;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\solaire_sec\Hook\Preprocess\NodePreprocess;
use Drupal\solaire_sec\Hook\Preprocess\Paragraph\ParagraphHelper;
use Drupal\views\ViewExecutable;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;


class ViewsViewFieldAlter {
  /**
   * Entity type manager for loading paragraph entities.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;
  protected EntityRepositoryInterface $entityRepository;

  /**
   * ParagraphPreprocess constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityRepositoryInterface $entity_repository
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityRepository = $entity_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Implements hook_theme_suggestions_views_view_fields_alter().
   */
  #[Hook('theme_suggestions_views_view_fields_alter')]
  public function viewsViewFieldAlter(array &$suggestions, array $variables): void {
      /** @var \Drupal\views\ViewExecutable $view */
      $view = $variables['view'];

      // Suggestion by view ID.
      $suggestions[] = 'views_view_fields__' . $view->id();

      // Suggestion by view ID and display ID.
      $suggestions[] = 'views_view_fields__' . $view->id() . '__' . $view->current_display;

      // Suggestion by view ID, display ID, and style plugin.
      $suggestions[] = 'views_view_fields__' . $view->id() . '__' . $view->current_display . '__' . $view->style_plugin->getPluginId();
  }

  /**
   * Implements hook_theme_suggestions_views_view_unformatted_alter().
   */
  #[Hook('theme_suggestions_views_view_unformatted_alter')]
  public function viewsViewUnformattedAlter(array &$suggestions, array $variables): void {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];

    if ($view->id() === 'solaire_frontpage' && $view->current_display === 'block_1') {
      $suggestions[] = 'views_view_unformatted__homepage_banner__hero';
    }

    if ($view->id() == 'offers' && $view->getDisplay()->getPluginDefinition()['id'] == 'block') {
      $suggestions[] = 'views_view_unformatted__offer_block';
    }

    if ($view->id() == 'related_content' && $view->getDisplay()->getPluginDefinition()['id'] == 'block') {
      $suggestions[] = 'views_view_unformatted__related_content_block';
    }
  }

  /**
   * Implements hook_theme_suggestions_views_view_alter().
   */
  #[Hook('theme_suggestions_views_view_alter')]
  public function viewsViewAlter(array &$suggestions, array $variables): void {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];

    // Suggestion by view ID.
    $suggestions[] = 'views_view__' . $view->id();

    // Suggestion by view ID and display ID.
    $suggestions[] = 'views_view__' . $view->id() . '__' . $view->current_display;
  }

  /**
   *
   */
  #[Hook('preprocess_views_view')]
  public function viewsViewPreprocess(array &$variables): void {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];

    if ($view->id() === 'related_content' || !empty($view->args[0])) {

      $node_preprocess = new NodePreprocess();
      $node = $node_preprocess->loadNodeById((int) $view->args[0]);

      if ($node) {
        if ($node->hasField('field_section') && !$node->get('field_section')->isEmpty()) {
          $ids = array_column(
            $node->get('field_section')->getValue(),
            'target_id'
          );

          $paragraphs = ParagraphHelper::loadParagraphsByIds($this->entityTypeManager, $ids);

          foreach($paragraphs as $paragraph) {
            if ($viewDisplay = ParagraphHelper::getParagraphFieldValue(
              $paragraph, 
              'field_views_display', 
              $this->entityRepository
            )) {
              $variables['section_view_display'] = $viewDisplay;
            }
          }
        }
      }
    }
  }
}
