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
   */
  public function taskEnableVocabularies() {

//    // Create a default vocabulary named "Tags"
//    $description = st('Use tags to group articles on similar topics into categories.');
//    $help = st('Enter a comma-separated list of words to describe your content.');
//    $vocabulary = (object) array(
//      'name' => st('Tags'),
//      'description' => $description,
//      'machine_name' => 'tags',
//      'help' => $help,
//    );
//
//    taxonomy_vocabulary_save($vocabulary);
  }

  /**
   * Setup some default blocks.
   */
  public function taskSetBlocks() {

    // Get all the aGov supported themes.
    $themes = agov_core_theme_info();

    parent::taskSetBlocks();

    // Set default system block in primary theme.
    Block::insertBlock('menu', 'menu-footer-sub-menu', $themes, 'footer', 3);

    // Set aGov blocks.
    Block::insertBlock('agov_text_resize', 'text_resize', $themes, 'header');
    Block::insertBlock('search', 'form', $themes, 'header');
    Block::insertBlock('workbench', 'block', $themes, 'content', -14);

    // Set aGov sidebar blocks.
    Block::insertBlock('menu', 'menu-quick-links', $themes, 'sidebar_second', -48);
    Block::insertBlock('agov_social_links', 'services', $themes, 'sidebar_second', -47);
  }

  /**
   * Insert the twitter block.
   */
  public function customTwitterBlock() {

    // Get all the aGov supported themes.
    $themes = agov_core_theme_info();

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

    Block::insertBlock('twitter_block', $bid, $themes, 'sidebar_second', -49);
  }


  /**
   * Set default variables.
   */
  public function taskSetVariables() {

    // Minimal sets most of our vars.
    parent::taskSetVariables();

    $variables = array();

    // Enable xmlsitemap includes for content types.
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
    // Get all the aGov supported themes.
    $themes = agov_core_theme_info();
    Bean::saveBean('basic_content', 'Footer copyright', 'The copyright message', '', $fields);
    Block::insertBlock('bean', 'footer-copyright', $themes, 'footer', 4, BLOCK_VISIBILITY_NOTLISTED, Block::NO_TITLE);
  }

  /**
   * Sets up news related beans.
   *
   * @todo: no current usage
   */
  public function taskDefaultNewsBeans() {

    $t = get_t();

    // Ensure the bean save function is available.
    if (!function_exists('Bean::saveBean')) {
      error_log('Function not available');
      return;
    }

    // Add menu links to main menu.
    $menus = array(
      'current-news' => 'news-media/news',
      'archived-news' => 'news-media/news-archive',
      'media-releases' => 'news-media/media-releases',
      'events' => 'news-media/events',
      'blog' => 'news-media/blog',
    );

    // @todo - Save these menu items below the news-media item.
    // Save the images required for these block.
    $files = array();
    $images = array(
      'news-media-intro' => 'news-media.jpg',
      'current-news' => 'news-archives.jpg',
      'archived-news' => 'news-archives.jpg',
      'media-releases' => 'media-releases.jpg',
      'events' => 'events.jpg',
      'blog' => 'news-media.jpg',
    );
    foreach ($images as $delta => $image) {
      // Load the files contents.
      $handle = fopen(dirname(__FILE__) . '/images/' . $image, 'r');
      // Returns the new file object.
      $files[$delta] = file_save_data($handle, 'public://' . $image, FILE_EXISTS_RENAME);
      fclose($handle);
    }

    // Install the required blocks.
    // Beans to be installed.
    $beans = array(
      // News and Media Intro block for the News & Media page.
      'news-media-intro' => array(
        'label' => $t('News and Media Intro'),
        'title' => '',
        'description' => $t('Provides a "News and Media Intro" bean that gives overview for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'hightlight',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['news-media-intro']->fid,
              'alt' => t('News and Media Intro'),
              'title' => t('News and Media Intro'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Optional intro block with image Est porttitor hac ultricies nec integer enim scelerisque proin sagittis porttitor, sit! Magnis sit egestas turpis parturient aliquam. Mauris duis nascetur vel porttitor scelerisque cursus nec, in dis sit sagittis, lacus lacus?',
              'format' => 'rich_text',
            ),
          ),
        ),
      ),

      // Current News block.
      'current-news' => array(
        'label' => $t('Current News'),
        'title' => $t('Current News'),
        'description' => $t('Provides a "Current News" bean for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'default',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['current-news']->fid,
              'alt' => t('Current News'),
              'title' => t('Current News'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Vel quis etiam enim pulvinar, tincidunt porttitor dignissim cum, dignissim sociis natoque, elementum nec scelerisque pulvinar, nascetur mauris mauris, a? Rhoncus augue et nunc, adipiscing tincidunt, sagittis, amet!',
              'format' => 'rich_text',
            ),
          ),
          // Link to field.
          'field_link_to' => array(
            '0' => array(
              'url' => $menus['current-news'],
              'title' => '',
              'attributes' => '',
            ),
          ),
        ),
      ),

      // Archived News block.
      'archived-news' => array(
        'label' => $t('Archived News'),
        'title' => $t('Archived News'),
        'description' => $t('Provides a "Archived News" bean for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'default',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['archived-news']->fid,
              'alt' => t('Archived News'),
              'title' => t('Archived News'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Vel quis etiam enim pulvinar, tincidunt porttitor dignissim cum, dignissim sociis natoque, elementum nec scelerisque pulvinar, nascetur mauris mauris, a? Rhoncus augue et nunc, adipiscing tincidunt, sagittis, amet!',
              'format' => 'rich_text',
            ),
          ),
          // Link to field.
          'field_link_to' => array(
            '0' => array(
              'url' => $menus['archived-news'],
              'title' => '',
              'attributes' => '',
            ),
          ),
        ),
      ),

      // Media releases.
      'media-releases' => array(
        'label' => $t('Media Releases'),
        'title' => $t('Media Releases'),
        'description' => $t('Provides a "Media Releases" bean for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'default',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['media-releases']->fid,
              'alt' => t('Media Releases'),
              'title' => t('Media Releases'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Vel quis etiam enim pulvinar, tincidunt porttitor dignissim cum, dignissim sociis natoque, elementum nec scelerisque pulvinar, nascetur mauris mauris, a? Rhoncus augue et nunc, adipiscing tincidunt, sagittis, amet!',
              'format' => 'rich_text',
            ),
          ),
          // Link to field.
          'field_link_to' => array(
            '0' => array(
              'url' => $menus['media-releases'],
              'title' => '',
              'attributes' => '',
            ),
          ),
        ),
      ),

      // Events.
      'events' => array(
        'label' => $t('Events'),
        'title' => $t('Events'),
        'description' => $t('Provides a "Events" bean for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'default',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['events']->fid,
              'alt' => t('Events'),
              'title' => t('Events'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Vel quis etiam enim pulvinar, tincidunt porttitor dignissim cum, dignissim sociis natoque, elementum nec scelerisque pulvinar, nascetur mauris mauris, a? Rhoncus augue et nunc, adipiscing tincidunt, sagittis, amet!',
              'format' => 'rich_text',
            ),
          ),
          // Link to field.
          'field_link_to' => array(
            '0' => array(
              'url' => $menus['events'],
              'title' => '',
              'attributes' => '',
            ),
          ),
        ),
      ),

      // Blog.
      'blog' => array(
        'label' => $t('Blog'),
        'title' => $t('Blog'),
        'description' => $t('Provides a "Blog" bean for the News & Media page.'),
        'type' => 'image_and_text',
        'view_mode' => 'default',
        'fields' => array(
          // Image field.
          'field_bean_image' => array(
            '0' => array(
              'fid' => $files['blog']->fid,
              'alt' => t('Blog'),
              'title' => t('Blog'),
            ),
          ),
          // Text field.
          'field_bean_text' => array(
            '0' => array(
              'value' => 'Vel quis etiam enim pulvinar, tincidunt porttitor dignissim cum, dignissim sociis natoque, elementum nec scelerisque pulvinar, nascetur mauris mauris, a? Rhoncus augue et nunc, adipiscing tincidunt, sagittis, amet!',
              'format' => 'rich_text',
            ),
          ),
          // Link to field.
          'field_link_to' => array(
            '0' => array(
              'url' => $menus['blog'],
              'title' => '',
              'attributes' => '',
            ),
          ),
        ),
      ),
    );

    // Create the beans.
    foreach ($beans as $bean) {
      Bean::saveBean($bean['type'], $bean['label'], $bean['description'], $bean['title'], $bean['fields'], $bean['view_mode']);
    }
  }

  /**
   * Created default tags.
   *
   * @todo: worker
   */
  public function taskCreateVocabTags() {
    // List of terms for insert.
    $terms = array(
      t('consequat'),
      t('fuisset'),
      t('maluisset'),
      t('ponderum'),
      t('prodesset'),
      t('rationibus'),
      t('voluptatibus'),
    );

    $vocabs = taxonomy_vocabulary_get_names();
    if (empty($vocabs['tags'])) {
      return;
    }

    // Save taxonomy terms.
    $vid = $vocabs['tags']->vid;
    $tids = array();

    foreach ($terms as $name) {
      $term = new \stdClass();
      $term->name = $name;
      $term->vid = $vid;
      $term->vocabulary_machine_name = 'tags';
      taxonomy_term_save($term);
      $tids[] = $term->tid;
    }

    // Save the tids.
    Variable::set('agov_tags_saved', $tids);
  }

  /**
   * Enable any modules.
   *
   * @todo: worker
   */
  public function taskEnableModules() {
//
//    // This enables all the content modules.
//    $modules = array(
//      'agov_beans',
//      'agov_layout',
//      'agov_front',
//      'agov_slideshow',
//      'agov_blog',
//      'agov_events',
//      'agov_media_releases',
//      'agov_news',
//      'agov_publications',
//      'agov_promotion',
//      // 'agov_permissions',
//    );
//
//    module_enable($modules);
//
//    drupal_set_message('Enabled modules for the full install.');
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
