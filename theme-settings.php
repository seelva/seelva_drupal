<?php
function seelva_form_system_theme_settings_alter(&$form, $form_state) {

  $form['seelva_settings'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => 99,
  );

  /**
  *
  * Add logo and favicon settings to vertical tabs
  *
  **/
  $form['logo']['#group'] = 'seelva_settings';
  unset($form['logo']['#attributes']['class']);
  $form['favicon']['#group'] = 'seelva_settings';

  /**
  *
  * Gneral settings
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

  $form['general_settings']['humans'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use humans.txt'),
    '#default_value' => theme_get_setting('html5'),
    '#description'   => t('Give credit to the developer using humans.txt file. humans.txt content must be edited outside drupal'),
  );

  /**
  *
  * Stylesheet exclusion settings
  *
  **/
  $form['seelva_styles'] = array(
    '#title' => t('Exclude stylesheets'),
    '#type' => 'fieldset',
    '#group' => 'seelva_settings',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['seelva_styles']['seelva_exclusion_info'] = array(
    '#markup' => variable_get('seelva_exclusion_info', t('<p>Disable module stylesheets</p>')),
  );

  $exclude = theme_get_setting('seelva_exclude_css');
  $counter = 1;
  foreach ($exclude as $name) {
    $form['seelva_styles'][ 'seelva_exclude_css_' . (string)$counter ] = array(
      '#type'          => 'checkbox',
      '#title'         => $name,
      '#default_value' => theme_get_setting( 'seelva_exclude_css_' . (string)$counter ),
    );

    $counter++;
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
