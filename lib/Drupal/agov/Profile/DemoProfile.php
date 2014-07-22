<?php

/**
 * @file
 * Contains a DemoProfile.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Profile;

/**
 * Class DemoProfile
 *
 * @package Drupal\agov\Profile
 */
class DemoProfile extends StandardProfile {

  /**
   * Returns modules and functions to install default/example content.
   *
   * @deprecated Will be removed when YAML lands.
   */
  public function defaultContentSettings() {

    return array(
      0 => array(
        'title' => st('Events'),
        'type' => 'module',
        'key' => 'agov_content_event',
      ),
      1 => array(
        'title' => st('Publications'),
        'type' => 'module',
        'key' => 'agov_content_publication',
      ),
      2 => array(
        'title' => st('Blog'),
        'type' => 'module',
        'key' => 'agov_content_blog',
      ),
      3 => array(
        'title' => st('Media Releases'),
        'type' => 'module',
        'key' => 'agov_content_media_release',
      ),
      4 => array(
        'title' => st('News Articles'),
        'type' => 'module',
        'key' => 'agov_content_news_article',
      ),
      5 => array(
        'title' => st('Promotions'),
        'type' => 'module',
        'key' => 'agov_content_promotion',
      ),
      6 => array(
        'title' => st('Standard pages'),
        'type' => 'module',
        'key' => 'agov_content_standard_page',
      ),
      7 => array(
        'title' => st('Default Beans'),
        'type' => 'function',
        'key' => '_agov_default_beans',
        'message' => st('Enabled Default Beans'),
      ),
      8 => array(
        'title' => st('Default News Beans'),
        'type' => 'function',
        'key' => '_agov_default_news_beans',
        'message' => st('Enabled Default News Beans'),
      ),
      9 => array(
        'title' => st('Default slides'),
        'type' => 'module',
        'key' => 'agov_content_slides',
      ),
    );
  }
}
