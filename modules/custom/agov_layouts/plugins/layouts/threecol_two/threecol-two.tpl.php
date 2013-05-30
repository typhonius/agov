<?php
/**
 * @file
 * Provides themed representation of the front layout.
 * @copyright Copyright(c) 2012 Previous Next Pty Ltd
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Tim Eisenhuth tim dot eisenhuth at previousnext dot com dot au
 *
 * Available variables
 * -------------------
 * $content array of panels.
 */

?>


<div class="ps-front-layout clearfix" <?php if (!empty($css_id)) : print "id=\"$css_id\""; endif; ?>>

  <div>
    <?php print $content['column_1']; ?>
  </div>
  
  <div>
    <?php print $content['column_2']; ?>
  </div>

  <div class="ps-col">
    <?php print $content['column_3']; ?>
  </div>
  
  <div class="ps-col-4">
    <?php print $content['column_4']; ?>
  </div>

  <div class="ps-col-5">
    <?php print $content['column_5']; ?>
  </div>

</div>
