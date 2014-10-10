<?php
/**
 * @file
 * The aGov install profile modules file.
 *
 * @copyright Copyright(c) 2014 PreviousNext
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext.com.au
 *
 * The Profile file contains functionality not possible to place in the
 * installer component, such as install tasks and forms.
 */

/**
 * Define the default install theme.
 */
define('AGOV_INSTALL_DEFAULT_THEME', 'agov_zen');

/**
 * Define a default theme constant
 */
define('AGOV_DEFAULT_THEME', 'agov_zen');

/**
 * Define the default admin theme.
 */
define('AGOV_DEFAULT_ADMIN_THEME', 'seven');

/**
 * Implements hook_install_tasks().
 */
function agov_install_tasks($install_state) {
  $tasks = array();

  $batch_has_run = variable_get('agov_example_content_batch', FALSE);

  // Task provides the profile selector and registration screens.
  $tasks['agov_installer_options'] = array(
    'display_name' => st('aGov options'),
    'display' => TRUE,
    'type' => 'form',
    'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
  );

  // Task installs example content, if required.
  $tasks['agov_example_content_install'] = array(
    'display_name' => st('Example content'),
    'display' => FALSE,
    'type' => 'batch',
    'run' => $batch_has_run ? INSTALL_TASK_RUN_IF_NOT_COMPLETED : INSTALL_TASK_SKIP,
  );

  return $tasks;
}

/**
 * Implements hook_install_tasks_alter().
 */
function agov_install_tasks_alter(&$tasks, $install_state) {

  $tasks['agov_finished'] = $tasks['install_finished'];
  unset($tasks['install_finished']);

  // @todo: Zen isnt being loaded correctly.
  agov_set_theme(AGOV_INSTALL_DEFAULT_THEME);
  drupal_add_css('profiles/agov/css/install.css');
  drupal_add_js('profiles/agov/js/install.js');
}

/**
 * Overrides the core install_finished.
 */
function agov_finished() {
  drupal_set_title(st('aGov installation complete'));
  $messages = drupal_set_message();
  $output = '<p>' . st('Congratulations, you installed aGov!') . '</p>';
  $output .= '<p>' . (isset($messages['error']) ? st('Review the messages above before visiting <a href="@url">your new site</a>.', array('@url' => url(''))) : st('<a href="@url" class="button">Visit your new site</a>', array('@url' => url('')))) . '</p>';

  // Flush all caches to ensure that any full bootstraps during the installer
  // do not leave stale cache data, and that any content types or other items
  // registered by the installation profile are registered correctly.

  variable_set('features_rebuild_on_flush', FALSE);
  drupal_flush_all_caches();

  // Remember the profile which was used.
  variable_set('install_profile', drupal_get_profile());

  // Installation profiles are always loaded last.
  db_update('system')
    ->fields(array('weight' => 1000))
    ->condition('type', 'module')
    ->condition('name', drupal_get_profile())
    ->execute();

  // Cache a fully-built schema.
  drupal_get_schema(NULL, TRUE);

  // Run cron to populate update status tables (if available) so that users
  // will be warned if they've installed an out of date Drupal version.
  // Will also trigger indexing of profile-supplied content or feeds.
  drupal_cron_run();
  variable_set('features_rebuild_on_flush', TRUE);

  return $output;
}

/**
 * Implements hook_install_configure_form_alter().
 *
 * Changes the inserted variables on the installer to some different defaults
 */
function agov_form_install_configure_form_alter(&$form, &$form_state) {

  // Set default values.
  $form['site_information']['site_name']['#default_value'] = 'aGov';
  $form['site_information']['site_mail']['#default_value'] = 'admin@' . $_SERVER['HTTP_HOST'];
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  $form['admin_account']['account']['mail']['#default_value'] = 'admin@' . $_SERVER['HTTP_HOST'];

  // Set the timezone to Canberra using a Custom TZ setting.
  $form['server_settings']['site_default_country']['#default_value'] = 'AU';
  $timezone_form = $form['server_settings']['date_default_timezone'];
  $sydney_tz = $timezone_form['#options']['Australia/Sydney'];
  $sydney_re = '/Sydney/';
  $canberra_tz = preg_replace($sydney_re, 'Canberra', $sydney_tz, 1);
  $timezone_form['#options']['Australia/Canberra'] = $canberra_tz;
  asort($timezone_form['#options']);
  $form['server_settings']['date_default_timezone'] = $timezone_form;

  // As a workaround to core issue #1017020 (http://drupal.org/node/1017020),
  // we override the timezone javascript behaviour by setting it to null in the
  // javascript file added below.
  $form['#attached']['js'] = array(
    drupal_get_path('module', 'agov_core') . '/js/agov_core.js' => array(
      'type' => 'file',
    ),
  );
}

/**
 * Install step agov_installer_options.
 *
 * We use this step to select the custom installer.
 */
function agov_installer_options() {

  set_time_limit(0);

  drupal_set_title('aGov options');

  /*
   * Profile setting.
   */
  $profiles = \Drupal\agov\Config\Profile::getProfiles();
  $options = array();
  foreach ($profiles as $key => $profile) {
    $options[$key] = $profile['name'];
  }

  $form = array();

  $form['profile'] = array(
    '#type' => 'fieldset',
    '#title' => st('Installation method'),
  );
  $form['profile']['installer'] = array(
    '#type' => 'radios',
    '#title' => st('Type of installer to use'),
    '#required' => TRUE,
    '#options' => $options,
    '#default_value' => \Drupal\agov\Config\Profile::DEFAULT_PROFILE,
  );

  foreach (array_keys($form['profile']['installer']['#options']) as $key) {
    $form['profile']['installer'][$key]['#description'] = $profiles[$key]['description'];
  }

  /*
   * Registration.
   */
  $form['register'] = array(
    '#type' => 'fieldset',
    '#title' => st('Register aGov'),
  );
  $form['register']['agov_register_confirm'] = array(
    '#type' => 'checkbox',
    '#title' => st('Register your aGov site'),
  );

  $form['register']['fields'] = array(
    '#prefix' => '<div id="agov-install-register-fields">',
    '#suffix' => '</div>',
  );

  // Default registration data.
  $default_data = array(
    'agov_register_agency' => variable_get('site_name'),
    'agov_register_email' => variable_get('site_mail'),
  );
  $form['register']['fields'][] = agov_register_form_elements($default_data, TRUE);

  /*
   * Submit.
   */
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => st('Continue'),
  );

  return $form;
}

/**
 * Validation callback for agov_installer_options.
 */
function agov_installer_options_validate($form, &$form_state) {
  if ($form_state['values']['agov_register_confirm']) {
    if (!valid_email_address($form_state['values']['agov_register_email'])) {
      form_set_error('agov_register_email', 'Please enter a valid email address.');
    }
  }
}

/**
 * Submit callback for agov_installer_options.
 */
function agov_installer_options_submit($form, &$form_state) {
  $values = $form_state['values'];

  // Run the installer.
  \Drupal\agov\Installer\Installer::doInstall($values['installer']);

  // If the user selects to register the site, post the data.
  if ($form_state['values']['agov_register_confirm']) {
    $data = array(
      'agov_register_agency' => check_plain($form_state['values']['agov_register_agency']),
      'agov_register_contact' => check_plain($form_state['values']['agov_register_contact']),
      'agov_register_email' => check_plain($form_state['values']['agov_register_email']),
      'agov_register_notification' => check_plain($form_state['values']['agov_register_notification']),
    );
    $registration = agov_register_post($data);
    if ($registration->code == 200) {
      drupal_set_message('Thank you, your registration was successful');
    }
    else {
      drupal_set_message('Sorry, your registration failed at this time. We will try again later to register your site. Visit `Configuration > aGov Registration` after installation to check the status.', 'warning');
    }

  }

  return FALSE;
}

/**
 * Sets the Batch API array for batch processing.
 */
function agov_example_content_install() {

  $operations = array();

  // @todo: Complex. Simplify.
  $profile = \Drupal\agov\Config\Profile::getSelectedProfile();
  $installer = new \Drupal\agov\Installer\Installer();
  $profile = $installer->getProfileHandler($profile);

  /* @var \Drupal\agov\Profile\DemoProfile $profile */
  $steps = $profile->defaultContentSettings();
  foreach ($steps as $content) {
    $operations[] = array(
      'agov_example_content_install_batch',
      array(
        $content['type'],
        $content['key'],
        $content['title'],
        isset($content['message']) ? $content['message'] : '',
      ),
    );
  }

  $batch = array(
    'operations' => $operations,
    'title' => st('Processing example content'),
    'init_message' => st('Initializing'),
    'error_message' => st('Error'),
    'finished' => 'agov_example_content_install_finished',
  );

  return $batch;
}

/**
 * Either enable module or run function for Example content batch install.
 *
 * @param string $type
 *   Type of content either 'module' or 'function'.
 * @param string $key
 *   Name of module or function to enable/run.
 * @param string $name
 *   Title/name of the module or function.
 * @param string $message
 *   Optional message to be output with function.
 * @param array $context
 *   Batch context data.
 */
function agov_example_content_install_batch($type, $key, $name, $message, &$context) {
  if ($type == 'module') {
    module_enable(array($key), FALSE);
    $context['results'][] = $key;
    $context['message'] = st('Enabled %module content.', array('%module' => $name));
  }
  elseif ($type == 'function') {
    call_user_func($key);
    $context['results'][] = $key;
    if ($message) {
      $context['message'] = $message;
    }
  }
}

/**
 * Performs any cleanup and output on completion of example content batch.
 */
function agov_example_content_install_finished(&$context) {

  drupal_set_message('Example content installed.');
}

/**
 * Force-set a theme at any point during the execution of the request.
 *
 * Drupal doesn't give us the option to set the theme during the installation
 * process and forces enable the maintenance theme too early in the request
 * for us to modify it in a clean way.
 *
 * This function was helpfully taken from Commerce Kickstart.
 *
 * @param string $target_theme
 *   Theme to set.
 */
function agov_set_theme($target_theme) {

  if ($GLOBALS['theme'] != $target_theme) {
    unset($GLOBALS['theme']);

    drupal_static_reset();
    $GLOBALS['conf']['maintenance_theme'] = $target_theme;
    _drupal_maintenance_theme();
  }
}
