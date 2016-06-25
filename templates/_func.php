<?php

/**
 * /site/templates/_func.php
 * 
 * Example of shared functions used by template files
 *
 * This file is currently included by _init.php 
 *
 * For more information see README.txt
 *
 */


/**
 * Given a group of pages, render a simple <ul> navigation
 *
 * This is here to demonstrate an example of a simple shared function.
 * Usage is completely optional.
 *
 * @param PageArray $items
 * @return string
 *
 */
function renderNav(PageArray $items) {

	// $out is where we store the markup we are creating in this function
	$out = '';

	// cycle through all the items
	foreach($items as $item) {

		// render markup for each navigation item as an <li>
		if($item->id == wire('page')->id) {
			// if current item is the same as the page being viewed, add a "current" class to it
			$out .= "<dt class='current'>";
		} else {
			// otherwise just a regular list item
			$out .= "<dt>";
		}

		// markup for the link
		$out .= "<a href='$item->url'>$item->title</a> ";

		// if the item has summary text, include that too
		if($item->summary) $out .= "<dd class='summary'>$item->summary</dd>";

		// close the list item
		$out .= "</dt>";
	}

	// if output was generated above, wrap it in a <ul>
	if($out) $out = "<dl class='nav'>$out</dl>\n";

	// return the markup we generated above
	return $out;
}



/**
 * Given a group of pages, render a <ul> navigation tree
 *
 * This is here to demonstrate an example of a more intermediate level
 * shared function and usage is completely optional. This is very similar to
 * the renderNav() function above except that it can output more than one
 * level of navigation (recursively) and can include other fields in the output.
 *
 * @param array|PageArray $items
 * @param int $maxDepth How many levels of navigation below current should it go?
 * @param string $fieldNames Any extra field names to display (separate multiple fields with a space)
 * @param string $class CSS class name for containing <ul>
 * @return string
 *
 */
function renderNavTree($items, $maxDepth = 0, $fieldNames = '', $class = 'nav') {

	// if we were given a single Page rather than a group of them, we'll pretend they
	// gave us a group of them (a group/array of 1)
	if($items instanceof Page) $items = array($items);

	// $out is where we store the markup we are creating in this function
	$out = '';

	// cycle through all the items
	foreach($items as $item) {

		// markup for the list item...
		// if current item is the same as the page being viewed, add a "current" class to it
		$out .= $item->id == wire('page')->id ? "<li class='current'>" : "<li>";

		// markup for the link
		$out .= "<a href='$item->url'>$item->title</a>";

		// if there are extra field names specified, render markup for each one in a <div>
		// having a class name the same as the field name
		if($fieldNames) foreach(explode(' ', $fieldNames) as $fieldName) {
			$value = $item->get($fieldName);
			if($value) $out .= " <div class='$fieldName'>$value</div>";
		}

		// if the item has children and we're allowed to output tree navigation (maxDepth)
		// then call this same function again for the item's children 
		if($item->hasChildren() && $maxDepth) {
			if($class == 'nav') $class = 'nav nav-tree';
			$out .= renderNavTree($item->children, $maxDepth-1, $fieldNames, $class);
		}

		// close the list item
		$out .= "</li>";
	}

	// if output was generated above, wrap it in a <ul>
	if($out) $out = "<ul class='$class'>$out</ul>\n";

	// return the markup we generated above
	return $out;
}

/**
 * @param $pa
 * @return string
 */
function mobileNavbarRecursive(PageArray $pa) {

	$out = '';
	foreach ($pa as $child) {
		$out .= "<li><a href='$child->url'>$child->title</a>";
		// If this child is itself a parent and not the root page, then render it's children in their own menu too...
		($child->numChildren && $child->id != 1) ? $out .= mobileNavbarRecursive($child->children) : '';
		$out .= '</li>';
	}

	return "<ul>{$out}</ul>";
}

/**
 * @param $pa
 * @return string
 */
function renderMobileNavbar(PageArray $pa) {
	$isSearchField = false;
	$out = '';
	foreach ($pa as $child) {
		$out .= "<li><a href='$child->url'>$child->title</a>";
		// If this child is itself a parent and not the root page, then render it's children in their own menu too...
		($child->numChildren && $child->id != 1) ? $out .= mobileNavbarRecursive($child->children) : '';
		$out .= '</li>';
	}

	if(!$isSearchField) {
		$out .= "<li>
                    <form class='mobile-search-form' action='". wire('pages')->get('template=search')->url ."' method='get'>
                        <input data-toggle='tooltip' data-placement='top' title='Search the site' style='width: 100%;' type=;text; name='q' placeholder='Search' value='" . wire('sanitizer')->entities(wire('input')->whitelist('q')) . "' />
                    </form>
                </li>";

		$isSearchField = true;
	}

	return "<ul>{$out}</ul>";
}


/**
 * @param $pa
 * @param string $title
 * @param null $root
 * @param string $output
 * @param int $level
 * @return string
 */
function renderChildrenOf(PageArray $items, $title = '', $root = null, $output = '', $level = 0) {
	if(!$root)
		$root = wire("pages")->get(1);
	($title === '') ? $output = "" : $output = "<li class='menu-text'>{$title}</li>";
	$level++;
	$childUrl = '#';
	foreach($items as $child) {
		$class = '';
		$has_children = count($child->children) ? true : false;

		if($has_children && $child !== $root) {
			$class .= 'dropdown menu'; // sub level Foundation dropdown li class
			$childUrl = $child->url; // stop parents being clickable
		} else {
                $childUrl = $child->url; // if does not have children, then get the page url
		}

		// make the current page and only its first level parent have an active class
		if($child === wire("page")){
			$class .= ' active';
		} else if($level == 1 && $child !== $root){
			if($child === wire("page")->rootParent || wire("page")->parents->has($child)){
				$class .= ' active';
			}
		}

		$class = strlen($class) ? " class='".trim($class)."'" : '';

		$output .= "<li$class><a href='$childUrl'>$child->title</a>";

		// If this child is itself a parent and not the root page, then render its children in their own menu too...
		if($has_children && $child !== $root) {
			$output .= renderChildrenOf($child->children, '', $root, $output, $level);
		}
		$output .= '</li>';
	}
	$outerclass = "dropdown menu";
	return "<ul class='$outerclass' data-dropdown-menu aria-expanded='true'>$output</ul>";
}


/**
 * @param Page $page
 * @return string
 * Note: could be replaced by the foundation component 'callout'
 */
function renderJumbotron(Page $page) {

	$out = "<h1 class='display-3'>{$page->title}</h1>";

	if($page->summary)
		$out .= "<p class='lead'>{$page->summary}</p>";

	return "<div class='jumbotron'>{$out}</div>";
}


/**
 * @param $images
 * @param array $options
 * @return string
 */
function renderCarousel($images, $options = array()) {
	// defaults options
	$defaults = array(
		'class' => 'main-carousel'
	);
	// merge user defined options
	$options = array_merge($defaults, $options);
	$out = '';
	foreach ($images as $image) {
		$out .= "<div><img src='$image->url'></div>";
	}

	return "<div class='{$options['class']}'>$out</div>";
}


/**
 * @param PageArray $items
 * @param array $options
 * @return string
 */
function renderAccordion(PageArray $items, $options = array()) {
	// defaults options
	$defaults = array(
		'allow_all_closed' => 'false',
		'multi_expand' => 'false',
		'link' => 'Visit »'
	);
	// merge user defined options
	$options = array_merge($defaults, $options);
	// $out is where we store the markup we are creating in this function
	$out = '';

	// cycle through all the items
	foreach($items as $item) {
		// if current item is the same as the page being viewed, add a "is-active" class to it
		if($item->id == wire('page')->id) {
			$out .= "<li class='accordion-item is-active' data-accordion-item>";
		}
		else {
			$out .= "<li class='accordion-item' data-accordion-item>";
		}

		$out .=	"<a href='#' class='accordion-title'>{$item->title}</a>";
		$out .= "<div class='accordion-content' data-tab-content>";
		if($item->summary) {
			$out .= "<p>$item->summary</p>";
		}
		else {
			$out .= "&nbsp;";
		}
		// link to the page
		$out .= "<a href='{$item->url}'>{$options['link']} </a>";
		// close accordion-content
		$out .= "<div>";
		// close the list item
		$out .= "</li>";
	}

	// if output was generated above, wrap it in a <ul>
	if($out) $out = "<ul class='accordion' data-accordion data-allow-all-closed={$options['allow_all_closed']}  data-accordion data-multi-expand={$options['multi_expand']}>$out</ul>\n";

	// return the markup we generated above
	return $out;
}


/**
 * @param PageArray $items
 * @param array $options
 * @return string
 */
function renderCallouts(PageArray $items, $options = array()) {
	// defaults options
	$defaults = array(
		'class' => 'framework-callouts'
	);
	// merge user defined options
	$options = array_merge($defaults, $options);
	// $out is where we store the markup we are creating in this function
	$out = '';
	// cycle through all the items
	foreach ($items as $item) {
		$out .= "<a href='{$item->link}'>
					<div class='medium-4 container-hover column'>
						<img src='{$item->image->url}'>
						<h6>{$item->title}</h6>
						<p>{$item->paragraph}</p>
						<p class='link'>{$item->link_name}</p>
					</div>
				</a>";
	}

	return "<div class='{$options['class']}'>{$out}</div>";
}
