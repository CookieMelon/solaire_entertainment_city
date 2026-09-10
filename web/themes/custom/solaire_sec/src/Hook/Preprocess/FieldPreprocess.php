<?php

namespace Drupal\solaire_sec\Hook\Preprocess;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\paragraphs\ParagraphInterface;

class FieldPreprocess {

  /**
   * Implements preprocess_field().
   */
  // #[Hook('preprocess_field')]
  // public function preprocessField(array &$variables) {
  //   $field_name = $variables['element']['#field_name'] ?? NULL;

  //   if ($field_name === 'field_section') {
  //     if ($variables['items']) {
  //       $variables['field_bundle'] = $this->getSectionDetails($variables['items']);
  //     }
  //   }
  // }

  /**
   * Preprocess section items.
   */
  // public function getSectionDetails(array $items) {
  //   foreach ($items as $item) {

  //     if ($item['content']['#paragraph'] instanceof ParagraphInterface) {
  //       $paragraph = $item['content']['#paragraph'];
  //       return $paragraph->bundle();
  //     }
  //   }
  // }
}
