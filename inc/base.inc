<?php
/**
 * @file
 * Container class for theme configuration.
 */

class seelva_theme_container {
  var $theme;
  var $settings;

  /**
   * @todo
   */
  function __construct($theme, $delta = NULL) {
    $this->theme = $theme;

    $this->init();

    seelva_alter('seelva', $this, $theme, $delta);
    return;
  }

  /**
   * @todo
   */
  function init() {
    if (!isset($this->settings)) {
      $this->settings = array(
        'exclude' => seelva_theme_get_setting('seelva_exclude', array(), $this->theme),
      );

      seelva_alter('seelva_settings', $this->settings, $this->theme);
    }

    return $this->settings;
  }
}