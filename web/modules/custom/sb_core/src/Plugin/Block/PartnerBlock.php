<?php

declare(strict_types = 1);

namespace Drupal\sb_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Partner' block.
 *
 * @Block(
 *  id = "partner_block",
 *  admin_label = @Translation("Cesem Partner block"),
 * )
 */
class PartnerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#theme' => 'cesem_partner',
    ];
  }

}
