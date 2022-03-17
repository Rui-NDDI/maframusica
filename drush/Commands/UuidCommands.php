<?php

namespace Drush\Commands;

use drush\drush;

/**
 * Force same uuid drush import.
 */
class UuidCommands extends DrushCommands {

  /**
   * Set UUID.
   *
   * @hook pre-command config:import
   */
  public function setUuid() {

    // Clear cache in order to prevent errors after upgrading drupal.
    drupal_flush_all_caches();
    // Sets a hardcoded site uuid right before `drush config:import`.
    $staticUuidIsSet = \Drupal::state()->get('static_uuid_is_set');
    if (!$staticUuidIsSet) {
      $config_factory = \Drupal::configFactory();
      $config_factory->getEditable('system.site')->set('uuid', '5c15a394-b9c8-45f2-9328-d856f109ae71')->save();
      Drush::output()->writeln('Setting the correct UUID for this project: done.');
      \Drupal::state()->set('static_uuid_is_set', 1);
    }

  }

}
