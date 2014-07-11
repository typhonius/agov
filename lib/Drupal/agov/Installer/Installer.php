<?php

/**
 * @file
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Installer;


use Drupal\agov\Exception\InvalidInstallProfileException;
use Drupal\agov\Profile\ProfileInterface;

class Installer {

  /**
   * Install a Profile.
   *
   * @param string $profile
   *   Name of the Profile to install.
   */
  public function install($profile = 'minimal') {

    ini_set('max_execution_time', '300');

    $installer = $this->getProfileHandler($profile);

    $installer->installProfile();
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
  protected function getProfileHandler($profile) {

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

    $profiles = array(
      'base' => array(
        'name' => t('Base'),
        'description' => t('Basic install'),
        'handler' => '\Drupal\agov\Profile\BaseProfile',
      ),
      'minimal' => array(
        'name' => t('Minimal'),
        'description' => t('Know how to build content types and configure Drupal? This install gives you maximum flexibility.'),
        'handler' => '\Drupal\agov\Profile\MinimalProfile',
      ),
      'standard' => array(
        'name' => t('Full install'),
        'description' => t('Want an aGov site up and running quickly with zero configuration? Use the Full install.'),
        'handler' => '\Drupal\agov\Profile\StandardProfile',
      ),
      'demo' => array(
        'name' => t('Demo install'),
        'description' => t('The Full install, plus demonstration content. Don\'t use this if you plan to build your site now.'),
        'handler' => '\Drupal\agov\Profile\DemoProfile',
      ),
    );

    return $profiles;
  }
}
