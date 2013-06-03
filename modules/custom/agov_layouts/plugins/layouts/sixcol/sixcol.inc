<?php
/**
 * @file
 * Defines the front panel layout
 * @copyright Copyright(c) 2012 Previous Next Pty Ltd
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Tim Eisenhuth tim dot eisenhuth at previousnext dot com dot au
 */

/**
 * Implementation of hook_panels_layouts().
 */
$plugin = array(
  'title' => t('Six Columns'),
  'category' => t('aGov'),
  'icon' => 'sixcol.png',
  'theme' => 'sixcol',
  'css' => 'sixcol.css',
  'admin css' => 'sixcol.admin.css',
  'regions' => array(
    'column_1' => t('Column 1'),
    'column_2' => t('Column 2'),
    'column_3' => t('Column 3'),
    'column_4' => t('Column 4'),
    'column_5' => t('Column 5'),
    'column_6' => t('Column 6'),
  ),
);
