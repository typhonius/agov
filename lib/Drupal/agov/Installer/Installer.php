<?php

/**
 * @file
 * Contains an Installer.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Installer;


use Drupal\agov\Config\Profile;
use Drupal\agov\Exception\InvalidInstallProfileException;
use Drupal\agov\Profile\ProfileInterface;

/**
 * Class Installer
 *
 * @package Drupal\agov\Installer
 */
class Installer {

  /**
   * Run the install.
   *
   * @param string $profile
   *   Name of the profile to use.
   */
  static public function doInstall($profile) {

    $installer = new static();

    $installer->install($profile);
  }

  /**
   * Install a Profile.
   *
   * @param string $profile
   *   Name of the Profile to install.
   */
  public function install($profile = NULL) {

    if (empty($profile)) {
      $profile = Profile::getSelectedProfile();
    }
    else {
      Profile::setSelectedProfile($profile);
    }

    $profile_settings = Profile::getProfile($profile);

    drupal_set_message('Installing ' . $profile_settings['name']);

    ini_set('max_execution_time', '300');

    try {
      $installer = $this->getProfileHandler($profile);

      $installer->installProfile();

      $installer->cleanup();
    }
    catch (\Exception $e) {
      drupal_set_message(sprintf('There was an error installing: %s', $e->getMessage()), 'error');
    }
  }

  /**
   * Get the handling class for a profile.
   *
   * @param string $profile
   *   The machine name of the profile.
   *
   * @return ProfileInterface
   *   A Profile class.
   *
   * @throws \Drupal\agov\Exception\InvalidInstallProfileException
   */
  public function getProfileHandler($profile) {

    $profiles = $this->profileMap();

    if (array_key_exists($profile, $profiles)) {
      if (class_exists($profiles[$profile]['handler'])) {

        return new $profiles[$profile]['handler']();
      }
    }

    throw new InvalidInstallProfileException();
  }

  /**
   * Fetch a list of available Profiles.
   *
   * @return array
   *   An array of profiles
   */
  public function profileMap() {

    return Profile::getProfiles();
  }
}
