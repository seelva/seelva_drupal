<?php
/**
 * Implements hook_process_HOOK()
 *
 */
function seelva_process_page(&$vars) {
 /**
 * Save variables to be printed/used in blocks instead of in page.tpl.php
 *
 */
  $seed = &drupal_static('seed');
  $seed['title_prefix'] = $vars['title_prefix'];
  $seed['title_suffix'] = $vars['title_suffix'];
  $seed['title'] = $vars['title'];
}


/**
 * Implements hook_process_HOOK()
 *
 */
function seelva_process_block(&$vars) {
  /**
   * Process blocks created with seed_tools:
   *  - seed_messages
   *  - seed_messages
   *  - seed_title
   */
  if ($vars['block']->module == 'seed_tools') {
    $seed = &drupal_static('seed');

    switch ($vars['block']->delta) {
      case 'seed_breadcrumb':
        $vars['content'] = '<div id="breadcrumb">' . theme('breadcrumb', array('breadcrumb' => drupal_get_breadcrumb())) . '</div>';
        break;

      case 'seed_messages':
        if ($seed['show_messages']) {
          $vars['content'] = theme('status_messages');
          unset($seed['show_messages']);
        }
        break;

      case 'seed_title':
        $vars['content'] = render($seed['title_prefix']);
        if ($seed['title']) {
          $vars['content'] .= '<h1 class="title" id="page-title">' . $seed['title'] . '</h1>';
        }
        $vars['content'] .= render($seed['title_suffix']);
        unset($seed['title_prefix']);
        unset($seed['title']);
        unset($seed['title_suffix']);
        break;

      case 'seed_sitename':
        if (current_path() == 'front') {
          $vars['content'] = '<h1 class="site-name">' . t('Welcome') . '</h1>';
        }
    }
  }
}

