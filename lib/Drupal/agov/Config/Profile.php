<?php

/**
 * @file
 * Contains a Config/Profile object.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Config;

use Drupal\agov\Exception\InvalidInstallProfileException;
use Drupal\fabricator\Worker\Variable;

/**
 * Class Profile
 *
 * @package Drupal\agov\Config
 */
class Profile {

  /**
   * Define the default profile.
   */
  const DEFAULT_PROFILE = 'standard';

  /**
   * Get the list of available profiles.
   *
   * @return array
   *   An array of profiles.
   */
  static public function getProfiles() {

    $profiles = array(
      'minimal' => array(
        'name' => t('Minimal'),
        'description' => t('Know how to build content types and configure Drupal? This install gives you maximum flexibility.'),
        'handler' => '\Drupal\agov\Profile\MinimalProfile',
      ),
      self::DEFAULT_PROFILE => array(
        'name' => t('Full install'),
        'description' => t('Want an aGov site up and running quickly with zero configuration? Use the Full install.'),
        'handler' => '\Drupal\agov\Profile\StandardProfile',
      ),
      'demo' => array(
        'name' => t('Demo install'),
        'description' => t("The Full install, plus demonstration content. Don't use this if you plan to build your site now."),
        'handler' => '\Drupal\agov\Profile\DemoProfile',
      ),
    );

    return $profiles;
  }

  /**
   * Get the selected profile.
   *
   * @return string
   *   The currently selected profile name.
   */
  static public function getSelectedProfile() {

    return Variable::get('agov_selected_profile', self::DEFAULT_PROFILE);
  }

  /**
   * Set the selected profile.
   *
   * @param string $profile
   *   The profile machine name.
   */
  static public function setSelectedProfile($profile = self::DEFAULT_PROFILE) {

    Variable::set('agov_selected_profile', $profile);
  }

  /**
   * Get a specific profile.
   *
   * @param string $profile
   *   The profile name
   *
   * @return array
   *   The profile setings.
   * @throws \Drupal\agov\Exception\InvalidInstallProfileException
   */
  static public function getProfile($profile) {

    $profiles = static::getProfiles();

    if (array_key_exists($profile, $profiles)) {
      return $profiles[$profile];
    }

    throw new InvalidInstallProfileException();
  }
}
