<?php

// basic-page.php template file 
// See README.txt for more information

// render a Jumbotron
$content = renderJumbotron($page);

// Primary content is the page's body copy
$content .= $page->body;

// If the page has children, then render navigation to them under the body.
// See the _func.php for the renderNav example function.
if($page->hasChildren) {
	$content .= renderAccordion($page->children, array('allow_all_closed'=>'true'));
}


