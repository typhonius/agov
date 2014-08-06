<?php

/**
 * @file
 * Contains a MinimalProfile
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Profile;

use Drupal\fabricator\Worker\Block;
use Drupal\fabricator\Worker\Filter;
use Drupal\fabricator\Worker\Permission;
use Drupal\fabricator\Worker\Variable;

/**
 * Class MinimalProfile
 *
 * @package Drupal\agov\Profile
 */
class MinimalProfile extends BaseProfile {

  /**
   * Install the Profile.
   *
   * The Installer will call this to run the installation process.
   */
  public function installProfile() {

    // Configure basic roles.
    $this->taskCreateRoles();

    // Create basic permissions.
    $this->taskCreatePermissions();

    // Configure default text formats and basic permissions.
    $this->taskSetTextFormats();

    // Set default front-end and admin themes.
    $this->taskSetThemes();

    // Set some PathAuto settings.
    $this->taskSetVariables();

    // Set up default terms.
    $this->taskEnableVocabularies();

    // Create additional date formats.
    $this->taskSetDateFormats();

    // Setup some default blocks.
    $this->taskSetBlocks();

    // Enable full image display mode.
    $this->taskFullImageDisplayMode();

    // Set default menu items.
    $this->taskSetMenuItems();

    // Set defaults for the contact form.
    $this->taskContactForm();

    // Enable any modules.
    $this->taskEnableModules();
  }

  /**
   * Set defaults for contact form.
   */
  public function taskContactForm() {

    // Set default recipient for contact form.
    db_update('contact')->fields(
      array('recipients' => Variable::get('site_mail', 'admin@example.com'))
    )
      ->condition('cid', 1)
      ->execute();
  }

  /**
   * Enable any modules.
   */
  public function taskEnableModules() {
    // By default, the minimal profile just gets what is in the .info file.
  }

  /**
   * Sets up default vocabularies.
   */
  public function taskEnableVocabularies() {

    // No vocabs created in the Minimal profile.
  }

  /**
   * Set up full display mode for media wysiwyg image display.
   */
  public function taskFullImageDisplayMode() {

    // @todo: Fabricate.
    Variable::set('field_bundle_settings_file__image', array(
      'view_modes' => array(
        'teaser' => array('custom_settings' => TRUE),
        'full' => array('custom_settings' => TRUE),
        'preview' => array('custom_settings' => TRUE),
        'rss' => array('custom_settings' => FALSE),
        'search_index' => array('custom_settings' => FALSE),
        'search_result' => array('custom_settings' => FALSE),
        'token' => array('custom_settings' => FALSE),
      ),
      'extra_fields' => array(
        'form' => array(),
        'display' => array(
          'file' => array(
            'default' => array(
              'weight' => '0',
              'visible' => TRUE,
            ),
            'full' => array(
              'weight' => '0',
              'visible' => TRUE,
            ),
          ),
        ),
      ),
    ));
  }

  /**
   * Setup some default blocks.
   */
  public function taskSetBlocks() {

    // Set default system block in primary theme.
    Block::insertBlock('system', 'main', 'content', -12);
    Block::insertBlock('system', 'help', 'content', -14);
    Block::insertBlock('superfish', '1', 'navigation');

    // Set aGov blocks.
    Block::insertBlock('search', 'form', 'header');
    Block::insertBlock('workbench', 'block', 'content', -14);

    // Set some blocks in the admin theme.
    $admin_theme = array(AGOV_DEFAULT_ADMIN_THEME);
    Block::insertBlock('system', 'main', 'content', 0, BLOCK_VISIBILITY_NOTLISTED, '', '', $admin_theme);
    Block::insertBlock('system', 'help', 'help', 0, BLOCK_VISIBILITY_NOTLISTED, '', '', $admin_theme);
    Block::insertBlock('agov_core', 'update_notification', 'help', 24, BLOCK_VISIBILITY_LISTED, '', "admin/reports/updates*", $admin_theme);
  }

  /**
   * Set default menu items.
   */
  public function taskSetMenuItems() {

    // Create a Home link in the main menu.
    $item = array(
      'link_title' => st('Home'),
      'link_path' => '<front>',
      'menu_name' => 'main-menu',
      'weight' => -50,
    );
    menu_link_save($item);

    menu_rebuild();
  }

  /**
   * Set default variables.
   *
   * @todo break this up
   */
  public function taskSetVariables() {

    $variables = array(

      // Pathauto default.
      'pathauto_node_pattern' => '[node:title]',
      'pathauto_punctuation_hyphen' => 1,
      'pathauto_taxonomy_term_pattern' => '[term:vocabulary]/[term:name]',
      'pathauto_user_pattern' => 'users/[user:name]',
      'path_alias_whitelist' => array(
        'node' => TRUE,
        'taxonomy' => TRUE,
        'user' => TRUE,
      ),

      // User registration.
      'user_register' => USER_REGISTER_ADMINISTRATORS_ONLY,
      'admin_theme' => AGOV_DEFAULT_ADMIN_THEME,
      'node_admin_theme' => AGOV_DEFAULT_ADMIN_THEME,

      // Turn on honeypot protection for all forms.
      'honeypot_protect_all_forms' => 1,
      // Don't disable page caching.
      'honeypot_time_limit' => "0",

      'xmlsitemap_settings_node_page' => array(
        'status' => '1',
        'priority' => '0.5',
      ),

    );

    Variable::setMany($variables);
  }

  /**
   * Add default text formats.
   *
   * @todo: Fabricate.
   */
  public function taskSetTextFormats() {

    // Add text formats.
    $filtered_html_format = array(
      'format' => 'filtered_html',
      'name' => 'Filtered HTML',
      'weight' => 0,
      'filters' => array(
        // URL filter.
        'filter_url' => array(
          'weight' => 0,
          'status' => 1,
        ),
        // HTML filter.
        'filter_html' => array(
          'weight' => 1,
          'status' => 1,
        ),
        // Line break filter.
        'filter_autop' => array(
          'weight' => 2,
          'status' => 1,
        ),
        // HTML corrector filter.
        'filter_htmlcorrector' => array(
          'weight' => 10,
          'status' => 1,
        ),
      ),
    );
    $filtered_html_format = (object) $filtered_html_format;
    filter_format_save($filtered_html_format);

    $rich_text_format = array(
      'format' => 'rich_text',
      'name' => 'Rich Text',
      'weight' => 0,
      'filters' => array(
        // URL filter.
        'filter_url' => array(
          'weight' => 0,
          'status' => 1,
        ),
        // HTML filter.
        'filter_html' => array(
          'weight' => 1,
          'status' => 1,
        ),
        // Line break filter.
        'filter_autop' => array(
          'weight' => 2,
          'status' => 1,
        ),
        // HTML corrector filter.
        'filter_htmlcorrector' => array(
          'weight' => 10,
          'status' => 1,
        ),
      ),
    );
    $rich_text_format = (object) $rich_text_format;
    filter_format_save($rich_text_format);

    $full_html_format = array(
      'format' => 'full_html',
      'name' => 'Full HTML',
      'weight' => 1,
      'filters' => array(
        // URL filter.
        'filter_url' => array(
          'weight' => 0,
          'status' => 1,
        ),
        // Line break filter.
        'filter_autop' => array(
          'weight' => 1,
          'status' => 1,
        ),
        // HTML corrector filter.
        'filter_htmlcorrector' => array(
          'weight' => 10,
          'status' => 1,
        ),
      ),
    );
    $full_html_format = (object) $full_html_format;
    filter_format_save($full_html_format);

    $perm_name_filtered = Filter::permissionName($filtered_html_format);
    Permission::grantBaseRoles(array($perm_name_filtered));
  }

  /**
   * Set default themes.
   */
  public function taskSetThemes() {

    // Nothing to do now.
  }

  /**
   * Sets a custom date format.
   *
   * This install function works in 3 steps:
   * 1) Install the date type - Month Year format ("F Y").
   * 2) Setup a date type of Publications.
   * 3) Using variable set we then associate the format and type.
   *
   * @todo: Fabricate.
   */
  public function taskSetDateFormats() {

    $t = get_t();

    // Variables.
    $date_formats = array(
      'agov_month_year' => array(
        'format' => array(
          'type' => 'agov_month_year',
          'format' => 'F Y',
          'locked' => TRUE,
          'is_new' => TRUE,
        ),
        'type' => array(
          'type' => 'agov_month_year',
          'title' => $t('Month Year Format'),
          'locked' => TRUE,
          'is_new' => TRUE,
        ),
      ),
      'agov_month_day_year' => array(
        'format' => array(
          'type' => 'agov_month_day_year',
          'format' => 'j F Y',
          'locked' => TRUE,
          'is_new' => TRUE,
        ),
        'type' => array(
          'type' => 'agov_month_day_year',
          'title' => $t('Month Day Year Format'),
          'locked' => TRUE,
          'is_new' => TRUE,
        ),
      ),
    );

    // Save the data.
    foreach ($date_formats as $type_string => $data) {
      system_date_format_save($data['format']);
      system_date_format_type_save($data['type']);
      Variable::set('date_format_' . $type_string, $data['format']['format']);
    }

    Variable::set('date_format_short', "d/m/Y - g:ia");
  }

  /**
   * Create basic roles.
   *
   * @todo: Fabricate
   */
  public function taskCreateRoles() {

    $weight = 1;
    $base_permissions = array();

    // Content editor.
    $roles['Content editor'] = array(
      'name' => 'Content editor',
      'weight' => $weight++,
      'permissions' => $base_permissions,
    );

    // Content approver.
    $roles['Content approver'] = array(
      'name' => 'Content approver',
      'weight' => $weight++,
      'permissions' => $base_permissions,
    );

    // Create the roles.
    foreach ($roles as $role) {
      $role_object = new \stdClass();
      $role_object->name = $role['name'];
      $role_object->weight = $role['weight'];

      // Save the role.
      user_role_save($role_object);

      // Grant permissions.
      user_role_grant_permissions($role_object->rid, $role['permissions']);
    }

    // Create a default role for site administrators, with all available
    // permissions assigned.
    $admin_role = new \stdClass();
    $admin_role->name = 'administrator';
    $admin_role->weight = 2;
    user_role_save($admin_role);
    user_role_grant_permissions($admin_role->rid, array_keys(module_invoke_all('permission')));
    // Set this as the administrator role.
    Variable::set('user_admin_role', $admin_role->rid);

    // Assign user 1 the "administrator" role.
    db_insert('users_roles')
      ->fields(array('uid' => 1, 'rid' => $admin_role->rid))
      ->execute();

    // Update the weight of the administrator role so its last.
    $admin_role = user_role_load($admin_role->rid);
    $admin_role->weight = $weight++;
    user_role_save($admin_role);
  }

  /**
   * Create basic permissions.
   *
   * @todo: This includes permissions for other modules.
   * @todo: Fabricate.
   */
  public function taskCreatePermissions() {

  // Enable default permissions for system roles.
    user_role_grant_permissions(
      DRUPAL_ANONYMOUS_RID,
      array(
        'access content',
        // These need to be moved...
//        'search page content',
//        'search blog_article content',
//        'search event content',
//        'search media_release content',
//        'search news_article content',
//        'search publication content',
      )
    );
    user_role_revoke_permissions(
      DRUPAL_ANONYMOUS_RID,
      array(
        'search all content',
      )
    );
    user_role_grant_permissions(
      DRUPAL_AUTHENTICATED_RID,
      array(
        'access content',
        'access comments',
        'post comments',
        'skip comment approval',
        // These need to be moved...
//        'search page content',
//        'search blog_article content',
//        'search event content',
//        'search media_release content',
//        'search news_article content',
//        'search publication content',
      )
    );
    user_role_revoke_permissions(
      DRUPAL_AUTHENTICATED_RID,
      array(
        'search all content',
      )
    );
  }
}
