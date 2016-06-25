<?php

// home.php (homepage) template file. 
// See README.txt for more information

$content = renderCarousel($page->images);

$content .= "<div class='row columns m-t-3'>". renderCallouts($page->callouts) ."</div>";

// Primary content is the page body copy and navigation to children.
$content .= "<div class='m-t-1'>{$page->body}</div>";

// See the _func.php file for the renderAccordion() function example
if($page->hasChildren()) {
    $content .= renderAccordion($page->children, array('allow_all_closed' => 'true'));
}
