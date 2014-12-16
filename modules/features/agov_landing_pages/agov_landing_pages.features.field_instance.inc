<?php
/**
 * @file
 * agov_landing_pages.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function agov_landing_pages_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-landing_page-field_content'
  $field_instances['node-landing_page-field_content'] = array(
    'bundle' => 'landing_page',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'compact' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'default' => array(
        'label' => 'hidden',
        'module' => 'paragraphs',
        'settings' => array(
          'view_mode' => 'full',
        ),
        'type' => 'paragraphs_view',
        'weight' => 1,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_content',
    'label' => 'Content',
    'required' => 0,
    'settings' => array(
      'add_mode' => 'select',
      'allowed_bundles' => array(
        'block' => 0,
        'image' => 0,
        'node' => 0,
        'section' => 0,
        'section_item' => 0,
        'text' => 0,
        'view' => 0,
      ),
      'default_edit_mode' => 'open',
      'title' => 'Widget',
      'title_multiple' => 'Widgets',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'paragraphs',
      'settings' => array(),
      'type' => 'paragraphs_embed',
      'weight' => 1,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Content');

  return $field_instances;
}
