<?php

/**
 * @file
 * Contains a Bean worker
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\fabricator\Worker;

/**
 * Class Bean
 *
 * @package Drupal\agov\Worker
 */
class Bean {

  /**
   * Helper to manufacture a new Bean of a given type.
   *
   * @param string $bean_type
   *   The type of bean to create
   * @param string $label
   *   Admin label for the bean
   * @param string $description
   *   (optional) Admin description for the bean
   * @param string $title
   *   (optional) Block title for the bean
   * @param array $fields
   *   (optional) An array of fields to assign. This should resemble the ACTUAL
   *   field array, as it is literally transposed onto the bean, with the
   *   exception that the language key should be omitted.
   * @param string $view_mode
   *   (optional) The view mode. Defaults to 'default'.
   */
  static public function saveBean($bean_type, $label, $description = '', $title = '', $fields = array(), $view_mode = 'default') {

    $bean = static::createBean($bean_type, $label, $description, $title, $fields, $view_mode);
    $bean->save();

    drupal_set_message('Created a new bean "' . $label . '" of type <em>' . $bean_type . '</em>');
  }


  /**
   * Create a bean from configuration.
   *
   * Normally, you can use agov_core_save_bean(), however this is useful if
   * you need to manipulate the bean before saving.
   *
   * @param string $bean_type
   *   The type of bean to create
   * @param string $label
   *   Admin label for the bean
   * @param string $description
   *   (optional) Admin description for the bean
   * @param string $title
   *   (optional) Block title for the bean
   * @param array $fields
   *   (optional) An array of fields to assign. This should resemble the ACTUAL
   *   field array, as it is literally transposed onto the bean, with the
   *   exception that the language key should be omitted.
   * @param string $view_mode
   *   (optional) The view mode. Defaults to 'default'.
   *
   * @return \Bean
   *   a Bean entity
   */
  static public function createBean($bean_type, $label, $description = '', $title = '', $fields = array(), $view_mode = 'default') {

    $config = array(
      'label' => $label,
      'description' => $description,
      'title' => $title,
      'type' => $bean_type,
      'view_mode' => $view_mode,
      'is_new' => TRUE,
    );
    foreach ($fields as $field_key => $field_value) {
      $config[$field_key] = array();
      $config[$field_key][LANGUAGE_NONE] = $field_value;
    }

    $bean = bean_create($config);

    return $bean;
  }
}
