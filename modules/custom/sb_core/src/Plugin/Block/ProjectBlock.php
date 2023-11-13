<?php

declare(strict_types = 1);

namespace Drupal\sb_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Project logo' block.
 *
 * @Block(
 *  id = "project_block",
 *  admin_label = @Translation("Cesem logo project block"),
 * )
 */
class ProjectBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#theme' => 'cesem_project',
    ];
  }

}
