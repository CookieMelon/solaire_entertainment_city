<?php

namespace Drupal\solaire_sec\Hook\Suggestion;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Template\Attribute;

class FormElementSuggestion {
  /**
   * Implements hook_theme_suggestions_form_element_alter().
   */
  #[Hook('theme_suggestions_form_element_alter')]
  public function themeSuggestionsFormElementAlter(
    array &$suggestions,
    array $variables,
  ): void {
    $element = $variables['element'] ?? [];

    if (($element['#type'] ?? NULL) === 'checkbox' && 
      $element['#webform_key'] === 'i_agree'
    ) {
      $suggestions[] = 'form_element__webform_checkbox_i_agree';
    }
  }

  /**
   * Implements hook_theme_suggestions_container_alter().
   */
  #[Hook('theme_suggestions_container_alter')]
  public function themeSuggestionsContainerAlter(
    array &$suggestions,
    array $variables,
  ): void {
    $element = $variables['element'] ?? [];

    if ($element && !is_null($element['#attributes'])) {
      if ($element['#attributes'] instanceof Attribute) {
        $attributes = $element['#attributes']->toArray();
        if (isset($attributes['id']) && $attributes['id'] === 'edit-i-agree--description') {
          $suggestions[] = 'container__webform_checkbox_i_agree';
        }
      }
    }
  }
}
