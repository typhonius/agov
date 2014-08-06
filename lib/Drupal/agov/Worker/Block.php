<?php

/**
 * @file
 * Contains a Block worker
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Worker;

/**
 * Class Block
 *
 * @package Drupal\agov\Worker
 */
class Block {

  /**
   * Create initial block placement for a block which hasn't been used before.
   *
   * @param string $module
   *   The module providing the block
   * @param string $delta
   *   The block delta
   * @param array $themes
   *   (optional) The theme to insert into. Defaults to the current theme
   * @param int|string $region
   *   (optional) The region to insert the block into. Defaults to
   *   BLOCK_REGION_NONE, so a block can be created but not assigned by leaving
   *   this blank.
   * @param int $weight
   *   (optional) The weight of the block. Defaults to 0.
   * @param int $visibility
   *   (optional) The visibility of the block.
   *   Defaults to BLOCK_VISIBILITY_LISTED.
   * @param string $pages
   *   (optional) The pages to show the block on. Defaults to all.
   *
   * @return bool
   *   TRUE if the block is inserted, or FALSE on an error.
   */
  static public function insertBlock($module, $delta, array $themes, $region = BLOCK_REGION_NONE, $weight = 0, $visibility = BLOCK_VISIBILITY_NOTLISTED, $pages = '') {

    $block_info = db_select('block', 'b')
      ->fields('b')
      ->execute()
      ->fetchAllAssoc('bid');

    $inserts = array();
    $updates = array();

    foreach ($themes as $theme) {

      // Replicate the compound key found on the block table.
      $compound_key = $theme . '-' . $module . '-' . $delta;

      $inserts[$compound_key] = array(
        'visibility' => (int) $visibility,
        'pages' => $pages,
        'module' => $module,
        'theme' => $theme,
        'status' => (int) ($region != BLOCK_REGION_NONE),
        'weight' => (int) $weight,
        'delta' => $delta,
        'cache' => DRUPAL_NO_CACHE,
        'region' => $region,
      );

      foreach ($block_info as $block) {

        $block_key = $block->theme . '-' . $block->module . '-' . $block->delta;

        // If an existing block is found, add to our update list and remove
        // from inserts.
        if ($block_key == $compound_key) {
          $updates[$compound_key] = $inserts[$compound_key] + array(
            'bid' => $block->bid,
          );

          unset($inserts[$compound_key]);
        }
      }
    }

    $fields = array(
      'visibility',
      'pages',
      'module',
      'theme',
      'status',
      'weight',
      'delta',
      'cache',
      'region',
    );

    if (!empty($inserts)) {
      $query = db_insert('block')->fields($fields);
      foreach ($inserts as $insert) {
        $query->values($insert);
      }
      $query->execute();
    }

    if (!empty($updates)) {
      foreach ($updates as $update) {
        $query = db_update('block')->fields($update)
          ->condition('bid', $update['bid'])
          ->execute();
      }
    }

    return TRUE;
  }

}
