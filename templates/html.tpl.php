<?php

/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 */
?>
<?php if ( !theme_get_setting('html5') ): ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if lt IE 7 ]> <html class="ie6" xmlns="http://www.w3.org/1999/xhtml" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" xmlns="http://www.w3.org/1999/xhtml" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" xmlns="http://www.w3.org/1999/xhtml" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" xmlns="http://www.w3.org/1999/xhtml" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0"<?php print $rdf_namespaces; ?>> <!--<![endif]-->
<?php else: ?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>"<?php print $rdf_namespaces; ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html dir="<?php print $language->dir; ?>" lang="<?php print $language->language; ?>"<?php print $rdf_namespaces; ?>> <!--<![endif]-->
<?php endif; ?>
<head profile="<?php print $grddl_profile; ?>">
	<?php print $head; ?>
	<title><?php print $head_title; ?></title>
	<?php print $styles; ?>

	<?php if ( theme_get_setting( 'humans' ) ): ?>
	<link rel="author" href="<?php print( drupal_get_path('theme', 'seelva') . '/humans.txt' ); ?>" />
	<?php endif; ?>

	<!-- Register the new HTML5 tags in older IE versions -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script>window.shim || document.write('<script src="<?php print( drupal_get_path('theme', 'seelva') . '/js/libs/html5.js' ); ?>"><\/script>')</script>
	<![endif]-->
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
	<div id="skip-links">
		<a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
	</div>
	<div id="outer">
		<div id="wrapper">
			<?php print $page_top; ?>
			<?php print $page; ?>
			<?php print $page_bottom; ?>
		</div>
	</div>
	<?php print $scripts; ?>
</body>
</html>
