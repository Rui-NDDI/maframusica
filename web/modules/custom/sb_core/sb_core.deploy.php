<?php

/**
 * @file
 * Deploy functions for Squarebit.
 *
 * This should only contain update functions that rely on the Drupal API and
 * need to run _after_ the configuration is imported.
 *
 * This is applicable in most cases. However, in case the update code enables
 * some functionality that is required for configuration to be successfully
 * imported, it should instead be placed in sb_core.post_update.php.
 */

declare(strict_types = 1);

/**
 * Import taxonomies.
 */
function sb_core_deploy_108000(): void {
  /** @var \Drupal\Core\Extension\ModuleInstallerInterface $module_installer */
  $module_installer = \Drupal::service('module_installer');
  // Import new content.
  $module_installer->install(['sb_content']);
  $module_installer->uninstall(['sb_content']);
}
