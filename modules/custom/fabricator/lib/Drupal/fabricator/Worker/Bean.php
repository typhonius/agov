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
   * @param string $machine_name
   *   Machine name (delta) to use for the bean
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
  static public function saveBean($bean_type, $machine_name, $label, $description = '', $title = '', $fields = array(), $view_mode = 'default') {

    $config = array(
      'delta' => $machine_name,
      'label' => $label,
      'description' => $description,
      'title' => $title,
      'type' => $bean_type,
      'view_mode' => $view_mode,
      'is_new' => TRUE,
    );

    $bean = bean_create($config);

    foreach ($fields as $field_key => $field_value) {
      $bean->$field_key[LANGUAGE_NONE] = $field_value;
    }

    $bean->default_revision = TRUE;

//    field_attach_insert('bean', $bean);

    $bean->save();

    drupal_set_message('Created a new bean "' . $label . '" of type <em>' . $bean_type . '</em>');

    drupal_flush_all_caches();
  }

}
