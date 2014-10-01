<?php

/**
 * @file
 * Contains a StandardProfile
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Profile;

use Drupal\fabricator\Worker\Bean;
use Drupal\fabricator\Worker\Block;
use Drupal\fabricator\Worker\Variable;

/**
 * Class StandardProfile
 *
 * @package Drupal\agov\Profile
 */
class StandardProfile extends MinimalProfile {

  /**
   * Define the default twitter widget ID.
   */
  const AGOV_TWITTER_WIDGET_ID = '360632154172030979';

  /**
   * Install the Profile.
   *
   * The Installer will call this to run the installation process.
   */
  public function installProfile() {

    parent::installProfile();

    // Ensure the beans feature is reverted.
    $revert = array('agov_beans');
    $this->revertFeatures($revert);

    // Set up custom blocks/beans.
    $this->customTwitterBlock();
    $this->taskSetBeans();

    // Set default tags.
    $this->taskCreateVocabTags();

    // Set defaults for the contact form.
    $this->taskContactForm();
  }

  /**
   * Sets up default vocabularies.
   *
   * @todo: Fabricate.
   */
  public function taskEnableVocabularies() {

    // Create a default vocabulary named "Tags"
    $description = st('Use tags to group articles on similar topics into categories.');
    $help = st('Enter a comma-separated list of words to describe your content.');
    $vocabulary = (object) array(
      'name' => st('Tags'),
      'description' => $description,
      'machine_name' => 'tags',
      'help' => $help,
    );

    taxonomy_vocabulary_save($vocabulary);
  }

  /**
   * Setup some default blocks.
   */
  public function taskSetBlocks() {

    parent::taskSetBlocks();

    // Set default system block in primary theme.
    Block::insertBlock('menu', 'menu-footer-sub-menu', 'footer', 3);

    // Set aGov blocks.
    Block::insertBlock('agov_text_resize', 'text_resize', 'header');
    Block::insertBlock('search', 'form', 'header');
    Block::insertBlock('workbench', 'block', 'content', -14);
    Block::insertBlock('views', 'slideshow-block', 'highlighted', 0, BLOCK_VISIBILITY_LISTED, '', '<front>');
    Block::insertBlock('views', 'footer_teaser-block', 'footer', 1);
    Block::insertBlock('menu_block', 'agov_menu_block-footer', 'footer');

    // Set aGov sidebar blocks.
    Block::insertBlock('menu', 'menu-quick-links', 'sidebar_second', -48);
    Block::insertBlock('agov_social_links', 'services', 'sidebar_second', -47);
  }

  /**
   * Insert the twitter block.
   */
  public function customTwitterBlock() {

    $data = array(
      "theme" => "",
      "link_color" => "",
      "width" => "",
      "height" => "",
      "chrome" => array(
        "noheader" => "noheader",
        "nofooter" => "nofooter",
        "noborders" => "noborders",
        "noscrollbar" => "noscrollbar",
        "transparent" => "transparent",
      ),
      "border_color" => "",
      "language" => "",
      "tweet_limit" => "",
      "related" => "",
      "polite" => "polite",
    );

    $bid = db_insert('twitter_block')
      ->fields(array(
        'info' => 'Twitter Feed',
        'widget_id' => self::AGOV_TWITTER_WIDGET_ID,
        'data' => serialize($data),
      ))
      ->execute();

    Block::insertBlock('twitter_block', $bid, 'sidebar_second', -49);
  }


  /**
   * Set default variables.
   */
  public function taskSetVariables() {

    // Minimal sets most of our vars.
    parent::taskSetVariables();

    $variables = array();

    // Enable xmlsitemap includes for content types.
    // @todo: should be added when each module is enabled
    $xmlsitemap_settings = array(
      'status' => '1',
      'priority' => '0.5',
    );

    $types = array(
      'node_page',
      'node_blog_article',
      'node_event',
      'node_footer_teaser',
      'node_media_release',
      'node_news_article',
      'node_page',
      'node_publication',
      'node_webform',
    );

    foreach ($types as $type) {
      $variables['xmlsitemap_settings_' . $type] = $xmlsitemap_settings;
    }

    Variable::setMany($variables);
  }

  /**
   * Setup beans.
   */
  public function taskSetBeans() {

    // Create the footer block.
    $this_year = date('Y');
    $fields = array(
      'field_bean_body' => array(
        '0' => array(
          'value' => '&#169; ' . $this_year . ' Government | Powered by <a href="http://agov.com.au">aGov</a>',
          'format' => 'rich_text',
        ),
      ),
    );

    Bean::saveBean('basic_content', 'Footer copyright', 'The copyright message', '', $fields);
    Block::insertBlock('bean', 'footer-copyright', 'footer', 4, BLOCK_VISIBILITY_NOTLISTED, Block::NO_TITLE);
  }

  /**
   * Created default tags.
   *
   * @todo: worker
   */
  public function taskCreateVocabTags() {

    // Nothing to do in Standard.
  }

  /**
   * Enable any modules.
   *
   * @todo: worker
   */
  public function taskEnableModules() {

    // This enables all the content modules.
    $modules = array(
      'agov_beans',
      'agov_front',
      'agov_slideshow',
      'agov_blog',
      'agov_events',
      'agov_media_releases',
      'agov_news',
      'agov_publications',
      'agov_promotion',
      'agov_permissions',
    );

    module_enable($modules);

    drupal_set_message('Enabled modules for the full install.');
  }

  /**
   * Run any cleanup or other functions required after install is finished.
   */
  public function cleanup() {

    parent::cleanup();

    // @todo Image styles not being applied to beans on install.
    features_revert(array('agov_beans' => array('field')));
  }

}
