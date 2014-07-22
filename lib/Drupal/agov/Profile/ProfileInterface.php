<?php

/**
 * @file
 * Contains a ProfileInterface.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Profile;

/**
 * Interface ProfileInterface
 *
 * @package Drupal\agov\Profile
 */
interface ProfileInterface extends ProfileTaskInterface {

  /**
   * Install the Profile.
   *
   * The Installer will call this to run the installation process.
   */
  public function installProfile();

  /**
   * Reverts a list of features.
   *
   * @param array $modules
   *   An array of feature modules to revert.
   */
  public function revertFeatures($modules);

  /**
   * Run any cleanup or other functions required after install is finished.
   */
  public function cleanup();

}
