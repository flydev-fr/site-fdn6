<?php

/**
 * _main.php
 * Main markup file
 *
 * This file contains all the main markup for the site and outputs the regions
 * defined in the initialization (_init.php) file. These regions include:
 *
 *   $title: The page title/headline
 *   $content: The markup that appears in the main content/body copy column
 *   $sidebar: The markup that appears in the sidebar column
 *
 * Of course, you can add as many regions as you like, or choose not to use
 * them at all! This _init.php > [template].php > _main.php scheme is just
 * the methodology we chose to use in this particular site profile, and as you
 * dig deeper, you'll find many others ways to do the same thing.
 *
 * This file is automatically appended to all template files as a result of
 * $config->appendTemplateFile = '_main.php'; in /site/config.php.
 *
 * In any given template file, if you do not want this main markup file
 * included, go in your admin to Setup > Templates > [some-template] > and
 * click on the "Files" tab. Check the box to "Disable automatic append of
 * file _main.php". You would do this if you wanted to echo markup directly
 * from your template file or if you were using a template file for some other
 * kind of output like an RSS feed or sitemap.xml, for example.
 *
 * See the README.txt file for more information.
 *
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $page->summary; ?>"/>
    <link href='//fonts.googleapis.com/css?family=Open%20Sans:300,400' rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/foundation.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/meanmenu.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>assets/css/main.css"/>
</head>
<body>


<!-- mobile device menu / navigation -->
<header id="mobile-menu" class="show-for-small-only">
    <div class="row">
        <div class="small-12 column">
            <nav>
                <?php
                $pa = $homepage->children;
                $pa = $pa->prepend($homepage);
                echo renderMobileNavbar($pa);
                ?>
            </nav>
        </div>
    </div>
</header>

<!-- top navigation for wide screen -->
<nav class="minimal-topbar top-bar hide-for-small-only">
    <div class="row">
        <div class="small-12 column">
            <div class="top-bar-left">
                <?php
                $pa = $homepage->children;
                $pa = $pa->prepend($homepage);
                echo renderChildrenOf($pa, '');
                ?>
            </div>
            <div class="top-bar-right">
                <ul class="menu">
                    <li>
                        <form action='<?php echo $pages->get('template=search')->url; ?>' method='get'>
                            <input class='search' type='text' name='q' placeholder='Search' value='<?php echo $sanitizer->entities($input->whitelist('q')); ?>'>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<!-- breadcrumbs -->
<div class="row ">
    <div class="small-12 column">
        <nav aria-label="You are here:" role="navigation">
            <ul class='breadcrumbs m-t-1'><?php
                // breadcrumbs are the current page's parents
                foreach ($page->parents() as $item) {
                    echo "<li><a href='$item->url'>$item->title</a></li> ";
                }
                // optionally output the current page as the last item
                echo "<li>$page->title</li> ";
                ?>
            </ul>
        </nav>
    </div>
</div>


<!-- content -->
<div class="row">
    <div class="small-12 column">
        <div id="main">
            <div id="content">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>


<!-- footer -->
<div class="row">
    <div class="small-12 columns">
        <footer id="footer" class="m-t-3">
            <p>
                Powered by <a href="http://processwire.com">ProcessWire CMS</a>
            </p>
        </footer>
    </div>
</div>

<script type="text/javascript">
    if (typeof jQuery == 'undefined') {
        document.write(unescape("%3Cscript src='<?php echo $config->urls->templates; ?>assets/js/jquery.js' type='text/javascript'%3E%3C/script%3E"));
    }
</script>
<script src="<?php echo $config->urls->templates; ?>assets/js/foundation.min.js"></script>
<script src="<?php echo $config->urls->templates; ?>assets/js/jquery.meanmenu.js"></script>
<script src="<?php echo $config->urls->templates; ?>assets/js/slick.min.js"></script>
<script src="<?php echo $config->urls->templates; ?>assets/js/main.js"></script>
<script>$(document).foundation();</script>

</body>
</html>
