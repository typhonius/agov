<?php

/**
 * @file
 * Contains a SameAs field.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */


$export = array(
  'fields' => array(
    'field_sameas' => array(
      'field_name' => 'field_sameas',
      'type' => 'link_field',
      'cardinality' => -1,
      'locked' => FALSE,
      'indexes' => array(),
      'settings' => array(
        'attributes' => array(
          'target' => 'default',
          'class' => '',
          'rel' => '',
        ),
        'url' => 0,
        'title' => 'optional',
        'title_value' => '',
        'title_maxlength' => 128,
        'enable_tokens' => 1,
        'display' => array(
          'url_cutoff' => 80,
        ),
      ),
      'storage' => array(
        'type' => 'field_sql_storage',
        'settings' => array(),
      ),
    ),
  ),
  'rdf_mappings' => array(
    array(
      'mapping' => array(
        'field_image' => array(
          'predicates' => array(
            0 => 'schema:sameas',
          ),
        ),
      ),
    ),
  ),
  'instances' => array(
    'field_sameas' => array(
      0 => array(
        'label' => 'Link',
        'widget' => array(
          'weight' => '31',
          'type' => 'link_field',
          'module' => 'link',
          'active' => 0,
          'settings' => array(),
        ),
        'settings' => array(
          'validate_url' => 1,
          'url' => 0,
          'title' => 'optional',
          'title_value' => '',
          'title_maxlength' => '128',
          'enable_tokens' => 1,
          'display' => array(
            'url_cutoff' => '80',
          ),
          'attributes' => array(
            'target' => 'default',
            'rel' => '',
            'class' => '',
            'configurable_title' => 0,
            'title' => '',
          ),
          'rel_remove' => 'default',
          'linkit' => array(
            'enable' => 0,
            'profile' => '',
            'button_text' => 'Search',
          ),
          'user_register_form' => FALSE,
        ),
        'display' => array(
          'default' => array(
            'label' => 'above',
            'type' => 'link_default',
            'settings' => array(),
            'module' => 'link',
            'weight' => 3,
          ),
          'blog_teaser' => array(
            'type' => 'hidden',
            'label' => 'above',
            'settings' => array(),
            'weight' => 0,
          ),
        ),
        'required' => 0,
        'description' => '',
        'default_value' => NULL,
        'field_name' => 'field_link',
        'entity_type' => 'node',
        'deleted' => '0',
      ),
    ),
  ),
);
