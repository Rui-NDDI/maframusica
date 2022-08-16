<?php

declare(strict_types = 1);

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
   * Adds a list of conditional state dependencies for each mentioned field.
   *
   * @param array $form
   *   An associative array containing the structure of the element.
   */
  public function applyMaterialityStates(array &$form): void {
    $this->buildsStatesElement('field_articulation_cover', [
      'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_articulation_system', [
      'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_associated_finial_point', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_associated_sewing_support', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_associated_tranchefil', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_attach_sewing_hold_folder', [
      'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_avg_dist_head_foot_sewing', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_avg_dist_sewing_stations', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_bifolios_notebook', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_binders', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_referenced_book', [
      'cover',
      'sewing',
      'sewing-thread',
      'decor',
      'bookbinding-book-body',
      'bookbinding-guard',
      'closures',
      'fitting-brooches',
      'fitting-labels-brands',
      'folders',
      'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_bookbinding_elements', [
      'bookbinding-guard',
    ], $form);
    $this->buildsStatesElement('field_book_body_composition', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_brooches', [
      'fitting-brooches',
    ], $form);
    $this->buildsStatesElement('field_category_book_body', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_closures', [
      'closures',
    ], $form);
    $this->buildsStatesElement('field_collation', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_color', [
      'cover', 'sewing-thread', 'sewing-support', 'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_color_finial_thread', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_color_skin_finial', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_cover_decoration', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_current_number', [
      'closures', 'fitting-brooches', 'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_decoration_type', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_decorative_finial', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_dimensions_book_body', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_book_dimensions_total', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_dyestuff', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_finial', [
      'sewing',
    ], $form);
    $this->buildsStatesElement('field_folders', [
      'folders',
    ], $form);
    $this->buildsStatesElement('field_folio_dimensions', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_format', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_image', [
      'cover', 'sewing', 'sewing-thread', 'decor', 'bookbinding-book-body', 'bookbinding-guard', 'closures', 'fitting-brooches', 'fitting-labels-brands', 'folders', 'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_inside_cover', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_label_brand_typology', [
      'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_loads', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_location', [
      'closures', 'fitting-brooches', 'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_local_seam_attach_gutter', [
      'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_material', [
      'cover', 'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_materiality_support', [
      'sewing-thread', 'decor', 'bookbinding-book-body', 'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_nature', [
      'cover', 'sewing-thread', 'sewing-support', 'decor', 'bookbinding-book-body', 'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_holes_number', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_number_reinforcements', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_observations', [
      'cover', 'sewing', 'sewing-thread', 'sewing-holes-tranchefil', 'sewing-support', 'decor', 'bookbinding-book-body', 'bookbinding-guard', 'closures', 'fitting-brooches', 'fitting-labels-brands', 'folders', 'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_original_number', [
      'closures', 'fitting-brooches', 'fitting-labels-brands',
    ], $form);
    $this->buildsStatesElement('field_others_not_associated', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_overturned_cover_posterior', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_overturned_cover_previous', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_path_around_sewing_support', [
      'sewing-thread',
    ], $form);
    $this->buildsStatesElement('field_pigment', [
      'decor',
    ], $form);
    $this->buildsStatesElement('field_references', [
      'cover', 'sewing', 'sewing-thread', 'sewing-holes-tranchefil', 'sewing-support', 'bookbinding-book-body', 'bookbinding-guard', 'closures', 'fitting-brooches', 'fitting-labels-brands', 'folders', 'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_regularity', [
      'bookbinding-guard', 'closures', 'fitting-brooches', 'folders', 'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_reinforcements', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_sewing_supports', [
      'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_sewing_order', [
      'sewing',
    ], $form);
    $this->buildsStatesElement('field_standard_dimension_folio', [
      'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_symmetry_holes_head_foot', [
      'sewing-holes-tranchefil',
    ], $form);
    $this->buildsStatesElement('field_tabs', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_tanning', [
      'cover', 'sewing', 'sewing-support',
    ], $form);
    $this->buildsStatesElement('field_thread_size_thicknes', [
      'sewing-thread',
    ], $form);
    $this->buildsStatesElement('field_thread_season', [
      'sewing-thread',
    ], $form);
    $this->buildsStatesElement('field_typology_cover', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_torsion', [
      'sewing-thread',
    ], $form);
    $this->buildsStatesElement('field_number_folios', [
      'sewing-support', 'bookbinding-book-body',
    ], $form);
    $this->buildsStatesElement('field_tranchefils', [
      'tranchefil',
    ], $form);
    $this->buildsStatesElement('field_typology_according_route', [
      'sewing',
    ], $form);
    $this->buildsStatesElement('field_typology_according_loop', [
      'sewing',
    ], $form);
    $this->buildsStatesElement('field_typology_according_books', [
      'sewing',
    ], $form);
    $this->buildsStatesElement('field_which_side', [
      'cover',
    ], $form);
    $this->buildsStatesElement('field_tranchephil_typology', [
      'tranchefil',
    ], $form);
  }

  /**
   * Builds a list of conditions to include in the Drupal states of an element.
   *
   * @param string $field
   *   The dependee field name.
   * @param array $values
   *   The list of values to display the fields.
   * @param array $form
   *   An associative array containing the structure of the element.
   */
  public function buildsStatesElement(string $field, array $values, array &$form): void {
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

    $form[$field]['#states']['visible'] = $options;
  }

}
