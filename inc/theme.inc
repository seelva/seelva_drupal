<?php

/**
* theme_menu_link()
*/
function seelva_menu_link(array $vars) {
  /**
  * Add classes to li
  **/
   $vars['element']['#attributes']['class'][] = 'menu-item';
  return theme_menu_link($vars);
}

/**
* theme_textarea()
**/

function seelva_textarea($vars) {
  /**
  * Removes re-sizable functionality on all text areas
  **/
  $element = $vars['element'];

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

/**
* theme_progress_bar()
**/
function seelva_progress_bar($vars) {
  /* Renders a progress bar using seelva markup style */
	$output = '<div class="progress">';
		$output .= '<div class="progressbar progressbar-info">';
			$output .= '<div class="progress-value" style="width: ' . $vars['percent'] . '%"><div class="progress-text">' . $vars['percent'] . '%</div></div>';
		$output .= '</div>';

		$output .= '<div class="message">' . $vars['message'] . '</div>';
	$output .= '</div>';

	return $output;
}

/**
* theme_pager()
**/
function seelva_pager($vars) {
  /* Render a pager using seelva markup */
	$tags = $vars['tags'];
	$element = $vars['element'];
	$parameters = $vars['parameters'];
	$quantity = $vars['quantity'];
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
						'class' => array('active', 'current'),
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
      'wrapper_attributes' => array('class' => array('pagination-wrapper', 'item-list')),
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

      if ($show_breadcrumb_home) {
        $output .= ' breadcrumb-item-home';
      }
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

/**
* Implements hook_THEME()
*
**/
function seelva_item_list($vars) {
  $items = $vars['items'];
  $title = $vars['title'];
  $type = $vars['type'];
  $attributes = $vars['attributes'];

  $wrapper_attributes = isset( $vars['wrapper_attributes'] ) ? $vars['wrapper_attributes'] : array();

  if ( !isset($wrapper_attributes['class']) ) {
    $wrapper_attributes['class'] = array('item-list');
  }

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 1) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items) {
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}

function seelva_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= '<div class="messages ' . $type . '">';
    $output .= '<a href="#close-msg" class="btn-close">&times;</a>';
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>";
    }
    if (count($messages) > 1) {
      $output .= '<ul>';
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . '</li>';
      }
      $output .= '</ul>';
    }
    else {
      $output .= $messages[0];
    }
    $output .= '</div>';
  }

  return $output;
}
