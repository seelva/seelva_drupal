<?php
function seelva_form_system_theme_settings_alter(&$form, $form_state) {
  dpm($form);

  $form['seelva_settings'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => 99,
  );

  /**
  * Add logo and favicon settings to vertical tabs
  *
  **/
  $form['logo']['#group'] = 'seelva_settings';
  unset($form['logo']['#attributes']['class']);
  $form['favicon']['#group'] = 'seelva_settings';

  /**
  * General settings
  *
  **/
  $form['general_settings'] = array(
    '#title' => t('General settings'),
    '#type' => 'fieldset',
    '#group' => 'seelva_settings',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['general_settings']['html5'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use HTML5 Doctype'),
    '#default_value' => theme_get_setting('html5'),
    '#description'   => t('If checked, seelva will use an HTML5 doctype declaration. If you decide to use an HTML5 doctype it is recommended to also use a helper module like !html5_tools', array('!html5_tools' => l('HTML5 Tools', 'http://drupal.org/project/html5_tools') )),
  );

  $form['general_settings']['html5shim'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use html5shim'),
    '#default_value' => theme_get_setting('html5shim'),
    '#description'   => t('If checked, seelva will use HTML5shim library to define new HTML5 tags in in legacy browsers. If you decide to use an HTML5 doctype it is recommended to also use a helper module like !html5_tools', array('!html5_tools' => l('HTML5 Tools', 'http://drupal.org/project/html5_tools') )),
  );

  $form['general_settings']['humans'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use humans.txt'),
    '#default_value' => theme_get_setting('humans'),
    '#description'   => t('Give credit to the developer using humans.txt file. humans.txt content must be edited outside drupal'),
  );

  /**
  * Stylesheet exclusion settings
  *
  **/
  $form['stylesheets'] = array(
    '#title' => t('Exclude stylesheets'),
    '#type' => 'fieldset',
    '#group' => 'seelva_settings',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['stylesheets']['seelva_exclusion_info'] = array(
    '#title' => t('seelva core stylesheets'),
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $seelva_stylesheets = drupal_parse_info_file( drupal_get_path('theme', 'seelva') . '/seelva.info' );
  $seelva_stylesheets = $seelva_stylesheets['stylesheets'];
  $counter = 1;

  foreach ($seelva_stylesheets as $item) {
    // Styleshets are represented as per-media arrays: print, screen...
    foreach ($item as $value) {
      if ($value == '--') {
        $form['stylesheets']['seelva_exclusion_info']['divider_' . (string)$counter ] = array(
          '#markup' => '<hr />',
        );
      }
      else {
        $form['stylesheets']['seelva_exclusion_info'][ 'seelva_exclude_css_' . (string)$counter ] = array(
          '#type'          => 'checkbox',
          '#title'         => $value,
          '#default_value' => theme_get_setting( 'seelva_exclude_css_' . (string)$counter ),
        );

        $counter++;
      }
    }
  }

  $form['stylesheets']['drupal_exclusion_info'] = array(
    '#title' => t('Drupal core stylesheets'),
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $core_stylesheets = theme_get_setting('seelva_exclude_css');
  $counter = 1;

  foreach ($core_stylesheets as $name) {
    if ($name == '--') {
      $form['stylesheets']['drupal_exclusion_info']['divider_' . (string)$counter ] = array(
        '#markup' => '<hr />',
      );
    }
    else {
      $form['stylesheets']['drupal_exclusion_info'][ 'drupal_exclude_css_' . (string)$counter ] = array(
        '#type'          => 'checkbox',
        '#title'         => $name,
        '#default_value' => theme_get_setting( 'drupal_exclude_css_' . (string)$counter ),
      );

      $counter++;
    }
  }

   /**
   * Breadcrumb settings
   * Copied from Zen
   */
  $form['seelva_breadcrumb'] = array(
   '#title' => t('Breadcrumb'),
   '#type' => 'fieldset',
    '#group' => 'seelva_settings',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['seelva_breadcrumb']['breadcrumb_separator'] = array(
   '#type'  => 'textfield',
   '#title' => t('Breadcrumb separator'),
   '#description' => t('Text only. Dont forget to include spaces.'),
   '#default_value' => theme_get_setting('breadcrumb_separator'),
   '#size' => 8,
   '#maxlength' => 10,
  );
  $form['seelva_breadcrumb']['breadcrumb_home'] = array(
   '#type' => 'checkbox',
   '#title' => t('Show the homepage link in breadcrumbs'),
   '#default_value' => theme_get_setting('breadcrumb_home'),
  );
  $form['seelva_breadcrumb']['breadcrumb_trailing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('breadcrumb_trailing'),
    '#description'   => t('Useful when the breadcrumb is placed just before the title.'),
  );
  $form['seelva_breadcrumb']['breadcrumb_title'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('breadcrumb_title'),
    '#description'   => t('Useful when the breadcrumb is not placed just before the title.'),
  );
}
