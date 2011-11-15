<?php  if (!defined('PmWiki')) exit();
/*  Copyright 2004, 2006 Nils Knappmeier
    Copyright 2004 Patrick R. Michaud (pmichaud@pobox.com)
    Copyright 2006 Hagan Fox
    Copyright 2006 Andy Kaplan-Myrth
    Copyright 2011 Tamara Temple (tamara@tamaratemple.com)

    This file is addlink.php; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published
    by the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.  

    Addlink.php creates an "add link" bookmarklet that makes it easy
    to bookmark pages you find while surfing the web into a wiki
    page.  This script was originally authored for PmWiki by Nils 
    Knappmeier, and updated for PmWiki 2 by Patrick R. Michaud on
    2004-11-30.  Modified by Hagan Fox on 2006-01-24 and 2006-04-09.
    Modified by Andy Kaplan-Myrth on 2006-05-31 to work with Kind-ofBlog.
    Modified by Tamara Temple on 2011-05 to remove dependency on KoB
	and work with any page (taking a half-step back to the original)

    More modifications by Tamara Temple on 2011/11/13:
	Nearly completely rewritten to allow the wiki owner to
	configure how they want the incoming link to be
	formatted. Bookmark format variables include:
	$AddLinkUrl = corresponds to the url query param
	$AddLinkTitle = corresponds to the title query param
	$AddLinkSelection = corresponds to the selection query param
	$AddLinkTags = corresponds to the tags param
	$AddLinkTime = set to current time

	No longer forcing the bookmark tag on submission, can be set
	via the $AddLinkFmt variable (see $DefaultAddLinkFmt)

	Also, the bookmarklet itself is completely rewritten to be
	browser-independent as well as opening a new window rather
	than changing the current tab/window's location.

    Install the script by copying the it to the cookbook/ directory
    and adding the following to the configuration file:

    ## Enable the AddLink Bookmarklet recipe.
    if ($action == 'edit' || $action == 'browse' || $action == 'addlink') {
      include_once("$FarmD/cookbook/addlink-tags2.php"); }

      If you want links to appear at the bottom of the page rather
      than the top, make sure to set $EnableAddLinkToEnd to 1 *before*
      including the recipe.

*/

// VERSION INFO
$RecipeInfo['AddLink2-tags']['Version'] = '2011/11/13';

$DefaultAddLinkFmt =
  "* [[\$AddLinkTitle -> \$AddLinkUrl]]\n->\$AddLinkSelection\n->Tags: (:tags \$AddLinkTags, bookmark:)\n-->Posted: \$AddLinkTime\n";

// VARIABLES
// Add links to the bottom instead of the top?
SDV($EnableAddLinkToEnd,0);
// What text should be added immediate before and after each new link?
// The default is a newline before and after.
SDV($AddLinkPrefixText,"\n");
SDV($AddLinkSuffixText,"\n");
// Format of the entry
SDV($AddLinkFmt,$DefaultAddLinkFmt);

// Add the (:addlink [PageName]:) markup and HandleAddLink actions.
Markup('addlink', 'inline', '/\\(:addlink\\s*(.*?):\\)/e', 
  "Keep(CreateBookmarklet(\$pagename,'$1'))");
$HandleActions['addlink'] = 'HandleAddLink';

// Function to create the bookmarklet
function CreateBookmarklet($pagename, $linkpage) {
  global $WikiTitle;
  if ($linkpage) $pagename = MakePageName($pagename, $linkpage);
  $bookmarklet="<a href=\"javascript:var tags, u, a, d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s = (e ? e() : (k) ? k() : (x ? x.createRange().text : 0 )),f = '\$PageUrl?action=addlink',l = d.location,en = encodeURIComponent;if (s == '') s = prompt('Descriptive Text:');tags=prompt('Enter comma-separated tags:');u = f + '?url=' + en(l.href) + '&title=' + en(d.title) + '&selection=' + en(s) + '&tags=' + en(tags);a = function () {if (!w.open(u, 't', '')) l.href = u;};if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0);else a();void(0)\" title=\"send to \$WikiTitle/\$pagename\">send to ".$WikiTitle."/".$pagename."</a>";
  return FmtPageName("Bookmarklet: $bookmarklet - drag to bookmark bar", $pagename);

}

// Use the site's default edit page.
if ($action=='addlink') {
  $action = 'edit';
  $OldEditHandler = $HandleActions[$action];
  $HandleActions[$action] = 'HandleAddLink';
}

// Function to handle ?action=addlink (prepends/appends the url to the page and
// then passes control to the edit function).
function HandleAddLink($pagename) {
  global  $OldEditHandler, $EnableAddLinkToEnd, $AddLinkPrefixText, $AddLinkSuffixText, $AddLinkFmt;
  Lock(2);
  $page = RetrieveAuthPage($pagename, 'edit');
  if (!$page) Abort("?cannot edit $pagename");
  $text = $page['text'];
  // Use similar method to map values into $AddLinkFmt as $UrlLinkFmt
  // in pmwiki.php 

  $AddLinkV = array();
  $AddLinkV['$AddLinkUrl'] = (isset($_REQUEST['url']))?urldecode($_REQUEST['url']):'';
  $t = (isset($_REQUEST['title']))?urldecode($_REQUEST['title']):'';
  $AddLinkV['$AddLinkTitle'] = str_replace("|", "-", $t); // this is to prevent the pipe from doing something in the link
  $AddLinkV['$AddLinkSelection'] = (isset($_REQUEST['selection']))?urldecode($_REQUEST['selection']):'';
  $AddLinkV['$AddLinkTags'] = (isset($_REQUEST['tags']))?urldecode($_REQUEST['tags']):'';
  $AddLinkV['$AddLinkTime'] = date("Y-n-j G:i");
  $newtext = str_replace(array_keys($AddLinkV),array_values($AddLinkV),$AddLinkFmt);

  if (IsEnabled($EnableAddLinkToEnd,0))
    $text .= $AddLinkPrefixText . $newtext . $AddLinkSuffixText;
  else
    $text = $AddLinkPrefixText . $newtext . $AddLinkSuffixText . $text;

  $action = 'edit';
  $_POST['text'] = $text;
  $OldEditHandler($pagename);
}


