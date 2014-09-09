<?php

/**
 * @file
 * Contains a File Field
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */


$export = array(
  'fields' => array(
    'field_file' => array(
      'field_name' => 'field_file',
      'type' => 'file',
      'cardinality' => -1,
      'locked' => FALSE,
      'indexes' => array('fid' => array('fid')),
      'settings' => array(
        'display_default' => 0,
        'display_field' => 0,
        'uri_scheme' => 'public',
      ),
      'storage' => array(
        'type' => 'field_sql_storage',
        'settings' => array(),
      ),
    ),
  ),
  'instances' => array(
    'field_file' => array(
      0 => array(
        'description' => 'Provides a file that wil be associated with this <em>page</em> - examples may include a downloadable version of the content.',
        'display' => array(
          'blog_teaser' => array(
            'label' => 'above',
            'settings' => array(),
            'type' => 'hidden',
            'weight' => 0,
          ),
          'default' => array(
            'label' => 'above',
            'module' => 'file',
            'settings' => array(),
            'type' => 'file_default',
            'weight' => 2,
          ),
        ),
        'ds_extras_field_template' => '',
        'label' => 'File',
        'required' => 0,
        'settings' => array(
          'description_field' => 0,
          'file_directory' => '',
          'file_extensions' => 'txt pdf docx doc xls xlsx ppt pptx',
          'max_filesize' => '',
          'user_register_form' => FALSE,
        ),
        'widget' => array(
          'active' => 1,
          'module' => 'file',
          'settings' => array(
            'allowed_schemes' => array(
              'public' => 'public',
              'vimeo' => 0,
              'youtube' => 0,
            ),
            'allowed_types' => array(
              'audio' => 0,
              'default' => 0,
              'image' => 'image',
              'video' => 0,
            ),
            'browser_plugins' => array(
              'library' => 0,
              'media_default--media_browser_1' => 0,
              'media_default--media_browser_my_files' => 0,
              'media_internet' => 0,
              'upload' => 0,
            ),
            'progress_indicator' => 'throbber',
          ),
          'type' => 'file_generic',
          'weight' => '3',
        ),
        'field_name' => 'field_file',
        'entity_type' => 'node',
        'deleted' => '0',
      ),
    ),
  ),
);
