<?php

/**
 * A wrapper function for theme_get_settings().
 *
 * @param $name
 *   The name of the setting that you want to retrieve.
 * @param $default (optional)
 *   The name (key) of the theme that you want to fetch the
 *   setting for. Defaults to NULL.
 * @param $theme (optional)
 *   The key (machin-readable name) of a theme. Defaults to the key of the
 *   current theme if not defined.
 *
 * @see
 *   theme_get_setting().
 */
function seelva_theme_get_setting($name, $default = NULL, $theme = NULL) {
  $setting = theme_get_setting($name, $theme);

  return isset($setting) ? $setting : $default;
}

/**
 * This function "fixes" drupal_alter so it also works in the theme-settings and anywhere else
 * where you want to be 100% certain that drupal_alter uses the proper global $theme.
 *
 * The problem with drupal_alter is, that it always relies on the global $theme while
 * the theme-settings page relies (and "overrides") the global $theme_key variable while
 * building its form.
 *
 * @param $type
 * @param $data
 * @param $context1
 * @param $context2
 *
 * @see
 *   See drupal_alter() for more information about how this works.
 */
function seelva_alter($type, &$data, &$context1 = NULL, &$context2 = NULL) {
  global $theme, $base_theme_info;

  if ($theme != $context1) {
    $themes = list_themes();

    if (!empty($themes[$context1])) {
      $theme_original = $theme;
      $base_theme_info_original = $base_theme_info;

      foreach (seelva_theme_trail($context1) as $key => $title) {
        if (isset($themes[$key])) {
          $base_theme_info[$key] = $themes[$key];
        }
      }

      $functions = &drupal_static('drupal_alter');

      if (!empty($base_theme_info)) {
        foreach ($base_theme_info as $item) {
          if (is_file(drupal_get_path('theme', $item->name) . '/template.php')) {
            include_once drupal_get_path('theme', $item->name) . '/template.php';
          }
        }
      }

      array_pop($base_theme_info);

      $theme = $context1;

      drupal_alter($type, $data, $context1, $context2);

      $theme = $theme_original;
      $base_theme_info = $base_theme_info_original;

      unset($functions[$type]);
    }
  }
  else {
    drupal_alter($type, $data, $context1, $context2);
  }
}


/**
 * Builds the full theme trail (deepest base theme first, subtheme last)
 * for a theme.
 *
 * @param $theme
 *   The key (machin-readable name) of a theme.
 *
 * @return
 *   An array of all themes in the trail, keyed by theme key.
 */
function seelva_theme_trail($theme) {
  $static = &drupal_static(__FUNCTION__);

  if (!isset($static)) {
    $themes = list_themes();

    if (isset($themes[$theme]->info['base theme'])) {
      foreach (system_find_base_themes($themes, $theme) as $base => $name) {
        if ($name && isset($themes[$base])) {
          $static[$theme][$base] = $themes[$base]->info['name'];
        }
      }
    }

    // Add our current subtheme ($key) to that array.
    if (isset($themes[$theme])) {
      $static[$theme][$theme] = $themes[$theme]->info['name'];
    }
  }

  if (isset($static[$theme])) {
    return $static[$theme];
  }
}

/**
 * Retrieves a .info element for the active theme trail.
 *
 * @param $item
 *   The name of the variable that you want to fetch.
 * @param $theme
 *   The key (machin-readable name) of a theme.
 * @return
 *   An array whoose keys are the theme names of the themes that provide
 *   information about $item. The array values represent the information about
 *   $item for the corresponding theme.
 */
function seelva_info_trail($item, $theme) {
  $output = array();

  if ($trail = seelva_theme_trail($theme)) {
    $themes = list_themes();

    foreach ($trail as $key => $name) {
      if (!empty($themes[$key]->info[$item])) {
        $output[$key] = $themes[$key]->info[$item];
      }
    }
  }

  return $output;
}

/**
 * Retrieve a list of CSS files that a theme may exclude via seelva_css_alter.
 *
 * @param $theme
 *   The key (machin-readable name) of a theme.
 *
 * @return
 *   An array of module and theme defined CSS files.
 */
function seelva_retrieve_excludes($theme) {
  $themes = list_themes();
  $styles = array();

  foreach (system_rebuild_module_data() as $module => $data) {
    if ($data->status && !empty($data->info['stylesheets'])) {
      foreach ($data->info['stylesheets'] as $media => $content) {
        foreach ($content as $file) {
          $styles[$file] = array(
            'type' => 'module',
            'source' => $module,
            'name' => $data->info['name'],
            'file' => $file,
            'media' => $media,
            'description' => NULL,
          );
        }
      }
    }
  }

  foreach (seelva_info_trail('stylesheets', $theme) as $item => $data) {
    foreach ($data as $media => $content) {
      foreach ($content as $file) {
        $styles[$file] = array(
          'type' => 'theme',
          'source' => $item,
          'name' => $themes[$item]->info['name'],
          'file' => $file,
          'media' => $media,
          'description' => NULL,
        );
      }
    }
  }

  foreach (seelva_info_trail('exclude', $theme) as $item => $data) {
    foreach ($data as $file => $description) {
      $styles[$file] = array(
        'type' => 'exclude',
        'source' => $item,
        'name' => $themes[$item]->info['name'],
        'file' => $file,
        'media' => NULL,
        'description' => $description,
      );
    }
  }

  return $styles;
}

/**
 * A helper function to return a proper options array for a form.
 *
 * @param $theme
 *   The key (machin-readable name) of a theme.
 *
 * @see
 *   hook_css_alter().
 *
 * @return
 *   An array of stylesheets that can be disabled / excluded with
 *   hook_css_alter().
 */

function seelva_exclude_options($theme) {
  $output = array();
  foreach (seelva_retrieve_excludes($theme) as $key => $info) {
    if ($info['type'] == 'exclude') {
      $output[$key] = '<strong>' . basename($key) . '</strong> - ' . t('Defined by') . ' ' . $info['name'] . '<div class="description">' . $info['description'] . '</div>';
    }
    else {
      $output[$key] = '<strong>' . basename($key) . '</strong> (' . $info['media'] . ') - ' . t('Belongs to') . ' ' . $info['name'] . '<div class="description">' . $info['description'] . '</div>';
    }
  }

  return $output;
}

/**
 * Returns the theme container object for the current theme.
 *
 * @return
 *   An object representing the current theme.
 */
function seelva_get_theme() {
  $container = &drupal_static(__FUNCTION__);

  $key = $theme = $GLOBALS['theme_key'];
  $delta = NULL;

  if (module_exists('delta') && $delta = delta_get_current($theme)) {
    $key .= ':' . $delta;
  }

  if (!isset($container[$key])) {
    foreach (array_keys(seelva_theme_trail($theme)) as $item) {
      if (class_exists($item . '_theme_container')) {
        $class = $item . '_theme_container';

        dsm($class);
      }
    }

    if (isset($class)) {
      $container[$key] = new $class($theme, $delta);
    }
  }

  return $container[$key];
}