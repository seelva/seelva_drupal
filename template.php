<?php
require_once dirname(__FILE__) . '/inc/preprocess.inc';
require_once dirname(__FILE__) . '/inc/process.inc';
require_once dirname(__FILE__) . '/inc/theme.inc';

/**
* Implements hook_html_head_alter()
*
**/
function seelva_html_head_alter(&$head_elements) {
  // Remove meta generator from html head
  unset($head_elements['system_meta_generator']);
}

/**
* Implements hook_css_alter()
*
**/
function seelva_css_alter(&$css) {
  /* Exclude certain stylsheets from stylesheets array */
  // Exclude seelva stylesheets
  $seelva_stylesheets = drupal_parse_info_file( drupal_get_path('theme', 'seelva') . '/seelva.info' );
  $seelva_stylesheets = $seelva_stylesheets['stylesheets'];
  $counter = 1;
  $exclude = array();

  foreach ($seelva_stylesheets as $item) {
    // Styleshets are represented as per-media arrays: print, screen...
    foreach ($item as $name) {
      if ($name != '--') {
        $saved_value = (bool)theme_get_setting( 'seelva_exclude_css_' . (string)$counter );

        if( $saved_value ) {
          $exclude[drupal_get_path('theme', 'seelva') . '/' . $name] = $saved_value;
        }

        $counter++;
      }
    }
  }

  // Exclude Drupal core stylesheets
  $drupal_stylesheets = theme_get_setting('seelva_exclude_css');

  $counter = 1;
  foreach ($drupal_stylesheets as $name) {
    if ($name != '--') {
      $saved_value = (bool)theme_get_setting('drupal_exclude_css_' . (string)$counter);

      if( $saved_value ) {
        $exclude[$name] = $saved_value;
      }

      $counter++;
    }
  }

  $css = array_diff_key($css, $exclude);
}

/**
* Implements hook_form_FORM_ID_alter()
*
**/
function seelva_form_user_login_alter(&$form, &$form_state, $form_id) {
  $actions_suffix = '<div class="actions-suffix">';
  $actions_suffix .= l(t('Request new password'), 'user/password', array('attributes' => array('class' => 'btn-login-password', 'title' => t('Get a new password'))));
  if (user_register_access()):
    $actions_suffix .= l(t('Create new account'), 'user/register', array('attributes' => array('class' => 'btn-login-register', 'title' => t('Create a new user account'))));
  endif;
  $actions_suffix .= '</div>';
  $form['actions']['#suffix'] = $actions_suffix;
}

function seelva_form_alter(&$form, &$form_state, $form_id){
  if ($form_id == 'search_form'){
      $form['advanced']['submit']['#prefix'] = '<div class="action form-actions">';
  }
}
