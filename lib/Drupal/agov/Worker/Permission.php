<?php

/**
 * @file
 * Contains a Permission Worker.
 *
 * @license GPL v2 http://www.fsf.org/licensing/licenses/gpl.html
 * @author Chris Skene chris at previousnext dot com dot au
 * @copyright Copyright(c) 2014 Previous Next Pty Ltd
 */

namespace Drupal\agov\Worker;

/**
 * Class Permission
 *
 * @package Drupal\agov\Worker
 */
class Permission {

  /**
   * Grant permissions for a given role.
   *
   * @param string $role
   *   The role name.
   * @param array $permissions
   *   An array of permission names.
   */
  static public function grantPermissions($role, array $permissions) {

    user_role_grant_permissions($role, $permissions);
  }

  /**
   * Grant a single permission for a given role.
   *
   * @param string $role
   *   The role name.
   * @param string $permission
   *   An array of permission names.
   */
  static public function grantPermission($role, $permission) {

    static::grantPermissions($role, array($permission));
  }

  /**
   * Grant permissions on the base roles.
   *
   * @param array $permissions
   *   An array of permission names.
   */
  static public function grantBaseRoles(array $permissions) {

    static::grantPermissions(DRUPAL_ANONYMOUS_RID, $permissions);

    static::grantPermissions(DRUPAL_AUTHENTICATED_RID, $permissions);
  }

}
