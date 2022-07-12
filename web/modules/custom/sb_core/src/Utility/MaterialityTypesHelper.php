<?php

namespace Drupal\sb_core\Utility;

/**
 * Provides a set of general helper methods for the materiality field states.
 */
class MaterialityTypesHelper {

  /**
   * The materiality types field name.
   *
   * @var string
   */
  public const MATERIALITY_TYPES_FIELD = 'field_materiality_type';

  /**
   * Builds a list of conditions to include in the Drupal states of an element.
   *
   * @param string $field
   *   The dependee field name.
   * @param array $values
   *   The list of values to display the fields.
   * @param array $element
   *   An associative array containing the structure of the element.
   */
  public function buildsStatesElement(string $field, array $values, array &$element): void {
    $options = [];

    foreach ($values as $index => $value) {
      $options[] = [
        'select[name="' . static::MATERIALITY_TYPES_FIELD . '"]' => [
          'value' => $value,
        ],
      ];

      if (count($values) > 1 && count($values) !== ($index + 1)) {
        $options[] = 'xor';
      }
    }

    $element[$field]['#states']['visible'] = $options;
  }

}
