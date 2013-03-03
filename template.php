<?php
/**
* Implements hook_css_alter()
*
**/
function seelva_css_alter(&$css) {
  /* Delete unused css files */
	$exclusion_files = theme_get_setting('seelva_exclude_css');
	$exclude = array();

	$counter = 1;
  foreach ($exclusion_files as $name) {
    $saved_value = (bool)theme_get_setting('seelva_exclude_css_' . (string)$counter);

    if( $saved_value ) {
      $exclude[$name] = $saved_value;
    }

    $counter++;
  }

  $css = array_diff_key($css, $exclude);
}

/**
* Implements hook_html_head_alter()
*
**/
function seelva_html_head_alter(&$head_elements) {
  // Remove meta generator from html head
  unset($head_elements['system_meta_generator']);
}

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
  * Removes the tabs from user  login, register & password. Also fixes page titles
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

    default:
      break;
  }
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
    }
  }
}

/**
* Removes re-sizable functionality on all text areas
**/
function seelva_textarea($variables) {
  $element = $variables['element'];

  $element['#attributes']['id'] = $element['#id'];
  $element['#attributes']['name'] = $element['#name'];
  $element['#attributes']['cols'] = $element['#cols'];
  $element['#attributes']['rows'] = $element['#rows'];
  _form_set_class($element, array('form-textarea'));

  $wrapper_attributes = array(
    'class' => array('form-textarea-wrapper')
  );

  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
  $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
  $output .= '</div>';
  return $output;
}


/* Renders a progress bar using seelva markup style */
function seelva_progress_bar($variables) {
	$output = '<div class="progress">';
		$output .= '<div class="progressbar progressbar-info">';
			$output .= '<div class="progress-value" style="width: ' . $variables['percent'] . '%"><div class="progress-text">' . $variables['percent'] . '%</div></div>';
		$output .= '</div>';

		$output .= '<div class="message">' . $variables['message'] . '</div>';
	$output .= '</div>';

	return $output;
}

function seelva_pager($variables) {
	$tags = $variables['tags'];
	$element = $variables['element'];
	$parameters = $variables['parameters'];
	$quantity = $variables['quantity'];
	global $pager_page_array, $pager_total;

	// Calculate various markers within this pager piece:
	// Middle is used to "center" pages around the current page.
	$pager_middle = ceil($quantity / 2);
	// current is the page we are currently paged to
	$pager_current = $pager_page_array[$element] + 1;
	// first is the first page listed by this pager piece (re quantity)
	$pager_first = $pager_current - $pager_middle + 1;
	// last is the last page listed by this pager piece (re quantity)
	$pager_last = $pager_current + $quantity - $pager_middle;
	// max is the maximum page number
	$pager_max = $pager_total[$element];
	// End of marker calculations.

	// Prepare for generation loop.
	$i = $pager_first;
	if ($pager_last > $pager_max) {
		// Adjust "center" if at end of query.
		$i = $i + ($pager_max - $pager_last);
		$pager_last = $pager_max;
	}
	if ($i <= 0) {
		// Adjust "center" if at start of query.
		$pager_last = $pager_last + (1 - $i);
		$i = 1;
	}
	// End of generation loop preparation.

	$li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
	$li_prev = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
	$li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
	$li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

	if ($pager_total[$element] > 1) {
		if ($li_first) {
			$items[] = array(
				'class' => array('pager-first'),
				'data' => $li_first,
			);
		}
		if ($li_prev) {
			$items[] = array(
				'class' => array('pager-previous'),
				'data' => $li_prev,
			);
		}

		// When there is more than one page, create the pager list.
		if ($i != $pager_max) {
			if ($i > 1) {
				$items[] = array(
					'class' => array('pager-ellipsis'),
					'data' => '…',
				);
			}
			// Now generate the actual pager piece.
			for (; $i <= $pager_last && $i <= $pager_max; $i++) {
				if ($i < $pager_current) {
					$items[] = array(
						'class' => array('pager-item'),
						'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
					);
				}
				if ($i == $pager_current) {
					$items[] = array(
						'class' => array('active'),
						'data' => '<span>' . $i . '</span>',
					);
				}
				if ($i > $pager_current) {
					$items[] = array(
						'class' => array('pager-item'),
						'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
					);
				}
			}
			if ($i < $pager_max) {
				$items[] = array(
					'class' => array('pager-ellipsis'),
					'data' => '…',
				);
			}
		}
		// End generation.
		if ($li_next) {
			$items[] = array(
				'class' => array('pager-next'),
				'data' => $li_next,
			);
		}
		if ($li_last) {
			$items[] = array(
				'class' => array('pager-last'),
				'data' => $li_last,
			);
		}
		return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
			'items' => $items,
			'attributes' => array('class' => array('pager', 'pagination')),
		));
	}
}

/**
 * Implements hook_THEME().
 *
 */
function seelva_breadcrumb($vars) {
  $breadcrumb = $vars['breadcrumb'];

  // Optionally get rid of the homepage link.
  $show_breadcrumb_home = theme_get_setting('breadcrumb_home');
  if (!$show_breadcrumb_home) {
    array_shift($breadcrumb);
  }

  // Return the breadcrumb with separators.
  if (empty($breadcrumb)) {
    return '';
  }

  $separator = filter_xss(theme_get_setting('breadcrumb_separator'));
  $trailing_separator = $title = '';

  // Add the title and trailing separator.
  if (theme_get_setting('breadcrumb_title')) {
    if ($title = drupal_get_title()) {
      if ( theme_get_setting('breadcrumb_separator') != '' ) {
        $trailing_separator = '<li class="breadcrum-separator"><span>' . $separator . '</span></li>';
      }
      $title = '<li class="breadcrum-item breadcrum-item-title"><span>' . drupal_get_title() . '</span></li>';
    }
  }
  // Just add the trailing separator.
  elseif ( theme_get_setting('breadcrumb_trailing') && theme_get_setting('breadcrumb_separator') != '' ) {
    $trailing_separator = '<li class="breadcrum-separator"><span>' . $separator .'</span></li>';
  }

  // Assemble the breadcrumb.
  $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
  $output .= '<ul class="breadcrumb">';

  $array_size = count($breadcrumb);
  $i = 0;
  while ( $i < $array_size) {
    $output .= '<li class="breadcrumb-item';
    if ($i == 0) {
      $output .= ' first';
    }
    if ($i+1 == $array_size) {
      $output .= ' last';
    }
    $output .=  '">' . $breadcrumb[$i] . '</li>';

    if ( $i+1 < $array_size && theme_get_setting('breadcrumb_separator') != '' ) {
      $output .= '<li class="breadcrum-separator"><span>' . $separator . '</span></li>';
    }
    $i++;
  }

  $output .= $trailing_separator . $title . '</ul>';

  return $output;
}
