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
interface ProfileInterface {

  /**
   * Install the Profile.
   *
   * The Installer will call this to run the installation process.
   */
  public function installProfile();

}
