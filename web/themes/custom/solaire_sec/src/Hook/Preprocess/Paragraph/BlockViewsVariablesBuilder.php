<?php

namespace Drupal\solaire_sec\Hook\Preprocess\Paragraph;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Plugin\Context\ContextHandlerInterface;
use Drupal\Core\Plugin\Context\ContextRepositoryInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\solaire_sec\Hook\Preprocess\Paragraph\ParagraphHelper;
use Drupal\views\ViewExecutable;

class BlockViewsVariablesBuilder {
  /** @var \Drupal\Core\Block\BlockManagerInterface */
  protected BlockManagerInterface $blockManager;

  /** @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface */
  protected ContextRepositoryInterface $contextRepository;

  /** @var \Drupal\Core\Plugin\Context\ContextHandlerInterface */
  protected ContextHandlerInterface $contextHandler;

  /** @var \Drupal\paragraphs\EntityRepositoryInterface */
  protected EntityRepositoryInterface $entityRepository;

  public function __construct(
    BlockManagerInterface $block_manager,
    ContextRepositoryInterface $context_repository,
    ContextHandlerInterface $context_handler,
    EntityRepositoryInterface $entity_repository
  ) {
    $this->blockManager = $block_manager;
    $this->contextRepository = $context_repository;
    $this->contextHandler = $context_handler;
    $this->entityRepository = $entity_repository;
  }

  public function buildBlockViewsVariables(ParagraphInterface $paragraph) {
    // Implement logic to build variables for block_views paragraph type.
    // This is a placeholder for the actual implementation.
    $result = [];

    if ($title = ParagraphHelper::getParagraphFieldValue($paragraph, 'field_title', $this->entityRepository)) {
      $result['block_title'] = $title;
    }

    if ($content = ParagraphHelper::getParagraphFieldValue($paragraph, 'field_content', $this->entityRepository)) {
      $result['block_content'] = $content;
    }


    if ($paragraph->hasField('field_views_block') && !$paragraph->get('field_views_block')->isEmpty()) {

      $items = ParagraphHelper::renderBlockField(
        $paragraph,
        'field_views_block',
        $this->blockManager,
        $this->contextRepository,
        $this->contextHandler
      );

      $view = $items['content']['#view'] ?? NULL;
      // Add a validation if views is empty.
      if ($view instanceof ViewExecutable && $view->total_rows > 0) {
        $result['block_views_content'][$paragraph->id()]['views_block'] = $items;
      }
    }

    return $result;
  }
}
