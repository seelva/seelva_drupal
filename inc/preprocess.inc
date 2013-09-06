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

  // Fixes page titles for login, register & password pages
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

  // If the current page is a node, add a class page-node-type-NODETYPE
  $node = menu_get_object();
  if ($node) {
    $vars['classes_array'][] = 'page-node-type-' . $node->type;
  }

   // Ensure that the $vars['rdf'] variable is an object.
  if (!isset($vars['rdf']) || !is_object($vars['rdf'])) {
    $vars['rdf'] = new StdClass();
  }

  // HTML attributes
  $vars['html_attributes_array'] = array();
  $vars['html_attributes_array']['dir'][] = $vars['language']->dir;
  $vars['html_attributes_array']['lang'][] = $vars['language']->language;
  $vars['html_attributes_array']['xml:lang'][] = $vars['language']->language;

  if ( !theme_get_setting('html5') ) {
    $vars['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
    $vars['html_attributes_array']['xmlns'][] = 'http://www.w3.org/1999/xhtml';
    $vars['html_attributes_array']['version'][] = 'XHTML+RDFa 1.0';
  }
  else {
    $vars['doctype'] = '<!DOCTYPE html>' . "\n";
  }

  // Use html5shiv.
  if (theme_get_setting('html5shim')) {
    $script1 = array(  
      'element' => array(
        '#tag' => 'script',
        '#value' => '',
        '#attributes' => array(
          'src' => '//html5shim.googlecode.com/svn/trunk/html5.js'
        ),
      )
    );

    $script2 = array(  
      'element' => array(
        '#tag' => 'script',
        '#value' => 'window.shim || document.write(\'<script src="' . drupal_get_path('theme', 'seelva') . '/js/libs/html5.js' . '"><\/script>\')'
      )
    );

    $html5shim = array(
      '#type' => 'markup',
      '#markup' => "<!-- Register the new HTML5 tags in older IE versions -->\n<!--[if lt IE 9]>\n\t" . theme('html_tag', $script1) . "\t" . theme('html_tag', $script2) . "<![endif]-->\n",
    );
    drupal_add_html_head($html5shim, 'seed_html5shim');
  }

  // Include humans.txt file
  if ( theme_get_setting( 'humans' ) ) {
    $link = array(
      'rel' => 'author',
      'href' => drupal_get_path('theme', 'seelva') . '/humans.txt'
    );
    drupal_add_html_head_link($link);
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

/**
* Implements THEME_preprocess_block()
*
**/
function seelva_preprocess_block(&$vars) {
  // Add block-id as class
  $vars['classes_array'][] = drupal_html_class($vars['block_html_id']);
  // $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['content_attributes_array']['class'][] = 'block-content';
}

/**
* Implements THEME_preprocess_views_view()
**/
function seelva_preprocess_views_view(&$vars) {
  /* Add a class in the form of view-VIEWNAME--VIEWDISPLAY-ID to every class */
  $vars['classes_array'][] = 'view-'. $vars['css_name'] . '--' . $vars['display_id'];
} 
