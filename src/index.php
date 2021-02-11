<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var JDocumentHtml $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Output as HTML5
$this->setHtml5(true);

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';


// Enable assets
$wa->usePreset('template.protostar4.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user')
	->useScript('template.user');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.protostar4.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));


// Use of Google Font
if ($this->params->get('googleFont'))
{
	$font = $this->params->get('googleFontName');

	// Handle fonts with selected weights and styles, e.g. Source+Sans+Condensed:400,400i
	$fontStyle = str_replace('+', ' ', strstr($font, ':', true) ?: $font);

	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $font);
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . $fontStyle . "', sans-serif;
	}");
}

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);


$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';
$backTop = $this->params->get('backTop');

// Adjusting content width
$hasClass = '';
if ($this->countModules('sidebar-left')) {
	$hasClass .= ' has-sidebar-left';
}
if ($this->countModules('sidebar-right')) {
	$hasClass .= ' has-sidebar-right';
}
$position7ModuleCount = false;
$position8ModuleCount = false;
if ($this->countModules('position-7') || $this->countModules('sidebar-right'))
{
	$position7ModuleCount = true;
}
if ($this->countModules('position-8') || $this->countModules('sidebar-left'))
{
	$position8ModuleCount = true;
}
if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}
$bottom_a_in_main = 1;
$bottom_b_in_main = 1;
// $bottom_a_in_main = $this->params->get('bottom_a_in_main', 0);
// $bottom_b_in_main = $this->params->get('bottom_b_in_main', 0);

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . htmlspecialchars(JUri::root() . $this->params->get('logoFile'), ENT_QUOTES) . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="site-grid site <?php echo $option
	. ' ' . $wrapper
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ' ' . $pageclass
	. $hasClass;
	echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
	<!-- Body -->
	<div class="body" id="top">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<!-- Header -->
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
						<?php if ($this->params->get('sitedescription')) : ?>
							<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
						<?php endif; ?>
					</a>
					<div class="header-search pull-right">
						<jdoc:include type="modules" name="position-0" style="none" />
						<jdoc:include type="modules" name="search" style="none" />
					</div>
				</div>
			</header>
			<?php if ($this->countModules('position-1')) : ?>
				<nav class="navigation" role="navigation">
					<div class="navbar pull-left">
						<a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
							<span class="element-invisible"><?php echo JTEXT::_('TPL_PROTOSTAR4_TOGGLE_MENU'); ?></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
					<div class="nav-collapse">
						<jdoc:include type="modules" name="position-1" style="none" />
						<jdoc:include type="modules" name="menu" style="none" />
					</div>
				</nav>
			<?php endif; ?>
			<jdoc:include type="modules" name="banner" style="none" />
			<div class="row-fluid">
				<?php if ($position8ModuleCount) : ?>
					<!-- Begin Sidebar -->
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="position-8" style="none" />
							<jdoc:include type="modules" name="sidebar-left" style="none" />
						</div>
					</div>
					<!-- End Sidebar -->
				<?php endif; ?>
				<main id="content" role="main" class="<?php echo $span; ?>">
					<!-- Begin Content -->
					<jdoc:include type="modules" name="breadcrumbs" style="none" />
					<?php if ($this->countModules('position-3') || $this->countModules('main-top')) : ?>
						<div class="main-top">
							<jdoc:include type="modules" name="position-3" style="none" />
							<jdoc:include type="modules" name="main-top" style="none" />
						</div>
					<?php endif; ?>
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<div class="clearfix"></div>
					<?php if ($this->countModules('position-2') || $this->countModules('main-bottom')) : ?>
						<div class="main-bottom">
							<jdoc:include type="modules" name="position-2" style="none" />
							<jdoc:include type="modules" name="main-bottom" style="none" />
						</div>
					<?php endif; ?>
					<!-- End Content -->
					<?php if ($this->countModules('bottom-a') && $bottom_a_in_main === 1) : ?>
						<div class="container-bottom-a">
							<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
								<jdoc:include type="modules" name="bottom-a" style="none" />
							</div>
						</div>
						<?php endif; ?>

						<?php if ($this->countModules('bottom-b') && $bottom_b_in_main === 1) : ?>
						<div class="grid-child container-bottom-b">
							<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
								<jdoc:include type="modules" name="bottom-b" style="none" />
							</div>
						</div>
						<?php endif; ?>
				</main>
				<?php if ($position7ModuleCount) : ?>
					<div id="aside" class="span3">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="well" />
						<jdoc:include type="modules" name="sidebar-right" style="none" />
						<!-- End Right Sidebar -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ($this->countModules('bottom-a') && $bottom_a_in_main === 0) : ?>
	<div class="container-bottom-a">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<jdoc:include type="modules" name="bottom-a" style="none" />
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this->countModules('bottom-b') && $bottom_b_in_main === 0) : ?>
	<div class="grid-child container-bottom-b">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<jdoc:include type="modules" name="bottom-b" style="none" />
		</div>
	</div>
	<?php endif; ?>
	<!-- Footer -->
	<?php if ($this->countModules('footer')) : ?>
	<footer class="footer" role="contentinfo">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
			<?php if ($backTop) : ?>
			<p class="pull-right">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR4_BACKTOTOP'); ?>
				</a>
			</p>
			<?php endif; ?>
		</div>
	</footer>
	<?php endif; ?>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
