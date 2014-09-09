<?php

/**
 * @file
 * Contains a field_image
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

$export = array(
  'fields' => array(
    'field_image' => array(
      'field_name' => 'field_image',
      'type' => 'image',
      'cardinality' => -1,
      'locked' => FALSE,
      'indexes' => array('fid' => array('fid')),
      'settings' => array(
        'uri_scheme' => 'public',
        'default_image' => FALSE,
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
            0 => 'schema:image',
          ),
        ),
      ),
    ),
  ),
  'instances' => array(
    'field_image' => array(
      0 => array(
        'description' => 'Provide a feature image that will be associated with this <em>Page</em>.  The image will be rendered above the page body text.',
        'display' => array(
          'default' => array(
            'label' => 'hidden',
            'module' => 'image',
            'settings' => array(
              'image_link' => '',
              'image_style' => 'article_page_620x349',
            ),
            'type' => 'image',
            'weight' => 0,
          ),
        ),
        'ds_extras_field_template' => '',
        'label' => 'Image',
        'required' => 0,
        'settings' => array(
          'alt_field' => 1,
          'default_image' => 0,
          'file_directory' => 'images/page',
          'file_extensions' => 'png gif jpg jpeg',
          'max_filesize' => '',
          'max_resolution' => '',
          'min_resolution' => '',
          'title_field' => 1,
          'user_register_form' => FALSE,
        ),
        'widget' => array(
          'active' => 1,
          'module' => 'media',
          'settings' => array(
            'allowed_schemes' => array(
              'public' => 'public',
              'vimeo' => 0,
              'youtube' => 0,
            ),
            'allowed_types' => array(
              'audio' => 0,
              'document' => 0,
              'image' => 'image',
              'video' => 0,
            ),
            'browser_plugins' => array(
              'media_default--media_browser_1' => 0,
              'media_default--media_browser_my_files' => 0,
              'media_internet' => 0,
              'upload' => 0,
            ),
            'progress_indicator' => 'throbber',
          ),
          'type' => 'media_generic',
          'weight' => '2',
        ),
        'field_name' => 'field_image',
        'entity_type' => 'node',
        'deleted' => '0',
      ),
    ),
  ),
);
