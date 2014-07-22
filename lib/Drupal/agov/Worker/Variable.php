<?php

/**
 * @file
 * Contains a Variable worker.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Worker;

/**
 * Class Variable
 *
 * @package Drupal\agov\Worker
 */
class Variable {

  /**
   * Set a variable.
   *
   * @param string $key
   *   The key to set.
   * @param mixed $value
   *   The value to set.
   */
  static public function set($key, $value) {
    variable_set($key, $value);
  }

  /**
   * Get a variable.
   *
   * @param string $key
   *   The key to get
   * @param mixed|null $default
   *   The value to return if none if found.
   *
   * @return mixed
   *   Result of the variable.
   */
  static public function get($key, $default = NULL) {
    return variable_get($key, $default);
  }

  /**
   * Set many variables.
   *
   * @param array $vars
   *   An array of key => value pairs to set.
   */
  static public function setMany(array $vars) {
    foreach ($vars as $key => $value) {
      static::set($key, $value);
    }
  }
}
