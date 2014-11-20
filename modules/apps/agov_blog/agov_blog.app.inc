<?php
/**
 * @file
 * Provides app info for agov blog.
 */

/**
 * Implements hook_apps_app_info().
 */
function agov_blog_apps_app_info() {
  return array(
    'demo content description' => 'Creates sample content to show blogs',
    'demo content module' => 'agov_content_blog',
    'permissions' => array(
      'create blog_article content' => array(
        'administrator' => 'administrator',
        'Site editor' => 'Site editor',
        'Site builder' => 'Site builder',
        'Content editor' => 'Content editor',
        'Content approver' => 'Content approver',
      ),
      'delete any blog_article content' => array(
        'administrator' => 'administrator',
        'Site editor' => 'Site editor',
        'Site builder' => 'Site builder',
        'Content editor' => 'Content editor',
        'Content approver' => 'Content approver',
      ),
      'delete own blog_article content' => array(
        'administrator' => 'administrator',
        'Site editor' => 'Site editor',
        'Site builder' => 'Site builder',
        'Content editor' => 'Content editor',
        'Content approver' => 'Content approver',
      ),
      'edit any blog_article content' => array(
        'administrator' => 'administrator',
        'Site editor' => 'Site editor',
        'Site builder' => 'Site builder',
        'Content editor' => 'Content editor',
        'Content approver' => 'Content approver',
      ),
      'edit own blog_article content' => array(
        'administrator' => 'administrator',
        'Site editor' => 'Site editor',
        'Site builder' => 'Site builder',
        'Content editor' => 'Content editor',
        'Content approver' => 'Content approver',
      ),
    ),
  );
}
