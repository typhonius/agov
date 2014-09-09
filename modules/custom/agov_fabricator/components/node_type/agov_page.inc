<?php

/**
 * @file
 * Contains a base page type.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

$export = array(

  // Default settings.
  'type' => 'agov_page',
  'name' => 'Standard Page',
  'base' => 'node_content',
  'description' => NULL,
  'custom' => 1,
  'modified' => 0,
  'locked' => 0,
  'disabled' => 0,
  'is_new' => 1,
  'has_title' => 1,
  'title_label' => 'Title',

  // Add a body field?
  'body_field' => TRUE,

  // RDF Mappings.
  'rdf_mappings' => array(
    array(
      'type' => 'node',
      'bundle' => 'agov_page',
      'mapping' => array(
        'rdftype' => array(
          0 => 'schema:Page',
          1 => 'sioc:Item',
          2 => 'foaf:Document',
        ),
        'title' => array(
          'predicates' => array(
            0 => 'schema:name',
          ),
        ),
        'created' => array(
          'predicates' => array(
            0 => 'dc:date',
            1 => 'dc:created',
          ),
          'datatype' => 'xsd:dateTime',
          'callback' => 'date_iso8601',
        ),
        'changed' => array(
          'predicates' => array(
            0 => 'dc:modified',
          ),
          'datatype' => 'xsd:dateTime',
          'callback' => 'date_iso8601',
        ),
        'body' => array(
          'predicates' => array(
            0 => 'content:encoded',
          ),
        ),
        'uid' => array(
          'predicates' => array(
            0 => 'sioc:has_creator',
          ),
          'type' => 'rel',
        ),
        'name' => array(
          'predicates' => array(
            0 => 'schema:name',
          ),
        ),
        'comment_count' => array(
          'predicates' => array(
            0 => 'sioc:num_replies',
          ),
          'datatype' => 'xsd:integer',
        ),
        'last_activity' => array(
          'predicates' => array(
            0 => 'sioc:last_activity_date',
          ),
          'datatype' => 'xsd:dateTime',
          'callback' => 'date_iso8601',
        ),
        'url' => array(
          'predicates' => array(
            0 => 'schema:url',
          ),
          'type' => 'rel',
        ),
      ),
    ),
  ),

  // Variables.
  'variables' => array(
    'node_options' => array('status'),
    'comment' => COMMENT_NODE_HIDDEN,
    'node_submitted' => FALSE,
  ),

  // Components.
  'components' => array(
    'agov_fabricator_field_image' => array(
      'weight' => 0,
    ),
    'agov_fabricator_field_file' => array(
      'weight' => 0,
    ),
    'agov_fabricator_field_sameas' => array(
      'weight' => 0,
    ),
  ),

);
