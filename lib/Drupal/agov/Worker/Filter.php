<?php

/**
 * @file
 * Contains a Filter worker
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Worker;


use Drupal\agov\Exception\aGovException;

class Filter {

  /**
   * Returns the machine-readable permission name for a provided text format.
   *
   * @param object|string $format
   *   An object representing a text format.
   *
   * @throws \Drupal\agov\Exception\aGovException
   * @return string|bool
   *   The machine-readable permission name, or FALSE if the provided text
   *   format is malformed or is the fallback format (which is available to
   *   all users).
   */
  static public function permissionName($format) {

    if (is_string($format)) {
      $format = filter_format_load($format);
    }

    if (isset($format->format) && $format->format != filter_fallback_format()) {
      return 'use text format ' . $format->format;
    }

    throw new aGovException();
  }
}
