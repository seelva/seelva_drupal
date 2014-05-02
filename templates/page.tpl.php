<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 */
?>

<?php if ( $page['topbar_before'] || $page['topbar'] || $page['topbar_after'] ): ?>
<div id="topbar" class="section">
	<div class="holder">
		<?php if ( $page['topbar_before']): print render($page['topbar_before']); endif; ?>
		<?php if ( $page['topbar']): print render($page['topbar']); endif; ?>
		<?php if ( $page['topbar_after']): print render($page['topbar_after']); endif; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( $page['header_before'] || $page['header'] || $page['header_after'] ): ?>
<header id="header" class="section">
	<div class="holder">
		<?php if ( $page['header_before']): print render($page['header_before']); endif; ?>
		<?php if ( $page['header']): print render($page['header']); endif; ?>
		<?php if ( $page['header_after']): print render($page['header_after']); endif; ?>
	</div>
</header> <!-- /#header -->
<?php endif; ?>

<?php if ( $page['menu'] ): ?>
<nav id="navigation" class="section">
	<div class="holder">
		<?php print render($page['menu']); ?>
	</div>
</nav> <!-- /#navigation -->
<?php endif; ?>


<?php if ( $page['teaser_before'] || $page['teaser'] || $page['teaser_after'] ): ?>
<div id="teaser" class="section">
	<div class="holder">
		<?php if ( $page['teaser_before']): print render($page['teaser_before']); endif; ?>
		<?php if ( $page['teaser']): print render($page['teaser']); endif; ?>
		<?php if ( $page['teaser_after']): print render($page['teaser_after']); endif; ?>
	</div>
</div>
<?php endif; ?>


<div id="main-wrapper" class="section">
	<div class="holder">
		<main id="content" class="column">
			<a id="main-content"></a>
			<?php if ($tabs): ?><div class="tabs-container"><?php print render($tabs); ?></div><?php endif; ?>
			<?php print render($page['help']); ?>
			<?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
			<?php if ( $page['content_before']): print render($page['content_before']); endif; ?>
			<?php print render($page['content']); ?>
			<?php if ( $page['content_after']): print render($page['content_after']); endif; ?>
		</main> <!-- /#content -->

		<?php if ($page['sidebar_first']): ?>
		<aside id="sidebar-first" class="column">
			<?php print render($page['sidebar_first']); ?>
		</aside>
		<?php endif; ?>

		<?php if ($page['sidebar_second']): ?>
		<aside id="sidebar-second" class="column">
			<?php print render($page['sidebar_second']); ?>
		</aside>
		<?php endif; ?>
	</div>
</div> <!-- /#main-wrapper -->

<?php if ( $page['appendix_before'] || $page['appendix'] || $page['appendix_after'] ): ?>
<div id="appendix" class="section">
	<div class="holder">
		<?php if ( $page['appendix_before']): print render($page['appendix_before']); endif; ?>
		<?php if ( $page['appendix']): print render($page['appendix']); endif; ?>
		<?php if ( $page['appendix_after']): print render($page['appendix_after']); endif; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( $page['footer_before'] || $page['footer'] || $page['footer_after'] ): ?>
<footer id="footer" class="section">
	<div class="holder">
		<?php if ( $page['footer_before']): print render($page['footer_before']); endif; ?>
		<?php if ( $page['footer']): print render($page['footer']); endif; ?>
		<?php if ( $page['footer_after']): print render($page['footer_after']); endif; ?>
	</div>
</footer><!-- /#footer -->
<?php endif; ?>
