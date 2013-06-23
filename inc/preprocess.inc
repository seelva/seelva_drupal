<?php

/**
 * Implements hook_preprocess_HOOK().
 *
 */
function seelva_preprocess_html(&$vars) {
  // Check for seed_tools due to dependency, alert if not found
  if (!module_exists('seed_tools')) {
    $vars['page']['page_top']['seed_tools_error'] = array(
      '#markup' => '<div="messages error">' . t('Module SeeD Tools (!seed_tools) is required for this theme to work properly', array('!seed_tools' => l('seed_tools', 'http://drupal.org/project/seed_tools'))) . "</div>",
    );
  }

  // Fixes page titles for login, register & password.
  switch (current_path()) {
    case 'user':
      $vars['head_title_array']['title'] = t('Login');
      $head_title = $vars['head_title_array'];
      $vars['head_title'] = implode(' | ', $head_title);
      break;

    case 'user/register':
      $vars['head_title_array']['title'] = t('Create new account');
      $head_title = $vars['head_title_array'];
      $vars['head_title'] = implode(' | ', $head_title);
      break;

    case 'user/password':
      $vars['head_title_array']['title'] = t('Request new password');
      $head_title = $vars['head_title_array'];
      $vars['head_title'] = implode(' | ', $head_title);
      break;

    case 'node':
    case 'front':

    default:
      break;
  }
}

/**
 * Implements hook_preprocess_HOOK().
 *
 */
function seelva_preprocess_page(&$vars) {
  /**
  * Save variables to be printed/used in blocks instead of in page.tpl.php
  **/
  $seed = &drupal_static('seed');
  if (isset($seed['messages_as_block']) && $seed['messages_as_block']) {
    $seed['show_messages'] = $vars['show_messages'];
    // If no messages yet, save as FALSE so that template_process_page does invoke
    // theme('status_messages')
    if (!isset($vars['messages'])) {
      $vars['messages'] = FALSE;
    }
  }
  unset($seed['messages_as_block']);

  /**
  * Removes the tabs from user login, register & password pages. Also fixes pages titles
  */
  switch (current_path()) {
    case 'user':
      $vars['title'] = t('Login');
      unset($vars['tabs']['#primary']);
      break;

    case 'user/register':
      $vars['title'] = t('Create new account');
      unset($vars['tabs']['#primary']);
      break;

    case 'user/password':
      $vars['title'] = t('Request new password');
      unset($vars['tabs']['#primary']);
      break;

    case 'front':
    default:
      break;
  }
}


/**
* Implements hook_preprocess_node()
*
**/
function seelva_preprocess_node(&$vars) {
  // Add a readmore variable accesible through $content['readmore']
  $vars['content']['readmore'] = $vars['content']['links']['node'];

  // Offer a theme file suggestion for teaser display
  if($vars['view_mode'] == 'teaser') {
    $vars['theme_hook_suggestions'][] = 'node__' . $vars['node']->type . '__teaser';
    $vars['theme_hook_suggestions'][] = 'node__' . $vars['node']->nid . '__teaser';
  }

  // Add a class per node-display: node-NODE-TYPE-DISPLAY
  $vars['classes_array'][] = 'node-' . $vars['node']->type . '-' . $vars['view_mode'];
}

