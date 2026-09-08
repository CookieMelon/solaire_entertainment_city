<?php

namespace Drupal\solaire_sec\Hook\Preprocess;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for solaire_sec.
 */
class ImageWidget {

  /**
   * Implements hook_preprocess_image_widget().
   */
  #[Hook('preprocess_image_widget')]
  public function preprocessImageWidget(array &$variables): void {
    $data = &$variables['data'];
    if (
      isset($data['preview']['#access'])
      && $data['preview']['#access'] === FALSE
    ) {
      unset($data['preview']);
    }
  }

}
