<?php

/**
 * @file
 * Contains a BaseProfile.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Profile;

/**
 * Class BaseProfile
 *
 * @package Drupal\agov\Profile
 */
abstract class BaseProfile implements ProfileInterface {

  /**
   * Install the Profile.
   *
   * The Installer will call this to run the installation process.
   */
  public function installProfile() {
    // Nothing to do here.
  }

  /**
   * Reverts a list of features.
   *
   * @param array $modules
   *   An array of feature modules to revert.
   */
  public function revertFeatures($modules) {

    module_load_include('inc', 'features', 'features.export');
    features_include();
    foreach ($modules as $module) {
      if (($feature = feature_load($module, TRUE)) && module_exists($module)) {
        $components = array();
        // Forcefully revert all components of a feature.
        foreach (array_keys($feature->info['features']) as $component) {
          if (features_hook($component, 'features_revert')) {
            $components[] = $component;
          }
        }
      }
      if (!empty($components)) {
        foreach ($components as $component) {
          features_revert(array($module => array($component)));
        }
      }
    }
  }

  /**
   * Run any cleanup or other functions required after install is finished.
   */
  public function cleanup() {

    // @todo: this should probably be somewhere else!
    if (module_exists('xmlsitemap')) {
      module_load_include('generate.inc', 'xmlsitemap');

      // Build a list of rebuildable link types.
      $rebuild_types = xmlsitemap_get_rebuildable_link_types();

      // Run the batch process.
      xmlsitemap_run_unprogressive_batch('xmlsitemap_rebuild_batch', $rebuild_types, TRUE);

      drupal_set_message('Sitemap has been rebuilt.');
    }

    // Rebuild default config (permissions).
    defaultconfig_rebuild_all();

    // Required by view_unpublished module.
    node_access_rebuild();

    cache_clear_all();
  }

  /**
   * Returns modules and functions to install default/example content.
   *
   * @deprecated Will be removed when YAML lands.
   */
  public function defaultContentSettings() {
    // Nothing to do here.
  }
}
