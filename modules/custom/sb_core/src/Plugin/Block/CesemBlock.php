<?php

declare(strict_types = 1);

namespace Drupal\sb_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Cesem' block.
 *
 * @Block(
 *  id = "cesem_block",
 *  admin_label = @Translation("Cesem block"),
 * )
 */
class CesemBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#markup' => '<p>' . $this->t('© @year CESEM – Centro de Estudos de Sociologia e Estética Musical', ['@year' => date("Y")]) . '</p>',
    ];
  }

}
