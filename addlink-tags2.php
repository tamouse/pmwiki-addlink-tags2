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

    Install the script by copying the it to the cookbook/ directory
    and adding the following to the configuration file:

    ## Enable the AddLink Bookmarklet recipe.
    if ($action == 'edit' || $action == 'browse' || $action == 'addlink') {
      include_once("$FarmD/cookbook/addlink-tags.php"); }

*/

// VERSION INFO
$RecipeInfo['AddLink2-tags']['Version'] = '2011/11/13';

$DefaultAddLinkFmt =
  "* [[\$AddLinkTitle -> \$AddLinkUrl]]\n->\$AddLinkSelection\n->Tags: (:tags \$AddLinkTags, bookmark:)\n-->Posted: \$posttime";

// VARIABLES
// Add links to the bottom instead of the top?
SDV($EnableAddLinkToEnd,0);
// What text should be added immediate before and after each new link?
// The default is a newline before and after.
SDV($AddLinkPrefixText,"\n");
SDV($AddLinkSuffixText,"\n");
SDV($AddLinkFmt,$DefaultAddLinkFmt);

// Add the (:addlink [PageName]:) markup and HandleAddLink actions.
Markup('addlink', 'inline', '/\\(:addlink\\s*(.*?):\\)/e', 
  "Keep(CreateBookmarklet(\$pagename,'$1'))");
$HandleActions['addlink'] = 'HandleAddLink';

// Function to create the bookmarklet
function CreateBookmarklet($pagename, $linkpage) {
	global $WikiTitle;
  if ($linkpage) $pagename = MakePageName($pagename, $linkpage);
  $bookmarklet="<a href=\"javascript:var tags, u, a, d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s = (e ? e() : (k) ? k() : (x ? x.createRange().text : 0 )),f = '\$PageUrl?action=addlink',l = d.location,en = encodeURIComponent;if (s == '') s = prompt('Descriptive Text:');tags=prompt('Enter comma-separated tags:');u = f + '?url=' + en(l.href) + '&title=' + en(d.title) + '&selection=' + en(s) + '&tags=' + en(tags+', bookmark');a = function () {if (!w.open(u, 't', 'toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570')) l.href = u;};if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0);else a();void(0)\" title=\"send to \$WikiTitle/\$pagename\">send to ".$WikiTitle."/".$pagename."</a>";
  //$mozlink = "<a href=\"javascript:selection=document.getSelection();if(!document.getSelection())selection=prompt('Text:');tags=prompt('Enter comma-separated tags:');t=document.title;t=t.replace('|','-');document.location.href='\$PageUrl?action=addlink&url='+encodeURIComponent(document.location.href)+'&selected='+encodeURIComponent(selection)+'&title='+encodeURIComponent(t)+'&tags='+encodeURIComponent(tags+', bookmark')\" title=\"send to \$WikiTitle.\">send to \$WikiTitle</a>";
  //  $ielink = "<a href=\"javascript:selection=document.selection.createRange().text;if(!selection)selection=prompt('Text:');tags=prompt('Enter comma-separated tags:');t=document.title;t=t.replace('|','-');document.location.href='\$PageUrl?action=addlink&url='+encodeURIComponent(document.location.href)+'&selected='+encodeURIComponent(selection)+'&title='+encodeURIComponent(t)+'&tags='+encodeURIComponent(tags+', bookmark')\" title=\"send to \$WikiTitle\">send to ".$WikiTitle."</a>";
	//@sms("mozlink=",htmlspecialchars($mozlink),__FILE__,__LINE__);
	//@sms("ielink=",htmlspecialchars($ielink),__FILE__,__LINE__);
  @sms("bookmarket=",htmlspecialchars($bookmarklet),__FILE__,__LINE__);
  return FmtPageName("Bookmarklet: $bookmarklet - drag to bookmark bar", $pagename);

}

// Use the site's default edit page.
// (I noticed in both addlink and kob-addlink that they set the $action variable, but then neglected to use it
// in the subsequent two statement - TT Tue May 31 20:42:31 CDT 2011)
if ($action=='addlink') {
  $action = 'edit';
  $OldEditHandler = $HandleActions[$action];
  $HandleActions[$action] = 'HandleAddLink';
}

// Function to handle ?action=addlink (prepends/appends the url to the page and
// then passes control to the edit function).
function HandleAddLink($pagename) {
  global  $OldEditHandler, $EnableAddLinkToEnd, $AddLinkPrefixText, $AddLinkSuffixText, $AddLinkFmt;
  $posttime = date("Y-n-j G:i");
  Lock(2);
  $page = RetrieveAuthPage($pagename, 'edit');
  if (!$page) Abort("?cannot edit $pagename");
  //  $text = addslashes($page['text']); // 2011/11/13 tpt -- do not know why this is here -- it causes problems with quotes in the wiki page
  $text = $page['text'];


  /* //	(this was part of kob-addlink.php for creating a blog entry. I went back to the original form somewhat adding in the space for selected text and tags - TT 2011-05-31) */
  /* if (@$_REQUEST['url']) { */
  /*   if (IsEnabled($EnableAddLinkToEnd,0)) */
  /*     $text .= "\n\n(:blogentry title=\"{$_REQUEST['title']}\" time=\"$posttime\" permalink=\"$pagename\":)\n(:tags {$_REQUEST['tags']}:)\n\n{$_REQUEST['selected']} -> [[{$_REQUEST['url']}|link]]\n\n(:blogentryend:)\n\n"; */
  /*    else $text = "\n(:blogentry title=\"{$_REQUEST['title']}\" time=\"$posttime\" permalink=\"$pagename\":)\n(:tags {$_REQUEST['tags']}:)\n\n{$_REQUEST['selected']} -> [[{$_REQUEST['url']}|link]]\n\n(:blogentryend:)\n\n" . $text; */

  /* } */

  /* if (@$_REQUEST['url']) { */
  /*   if (@$_REQUEST['title']) { */
  /*     $newtext = "[[{$_REQUEST['title']} -> {$_REQUEST['url']}]]"; */
  /*   } else { */
  /*     $newtext = $_REQUEST['url']; */
  /*   } */
  /* 	if (@$_REQUEST['selected']) { */
  /* 		$newtext .= "\n{$_REQUEST['selected']}"; */
  /* 	} */
  /* 	if (@$_REQUEST['tags']) { */
  /* 		// tags comes in URI encoded, but we need the spaces and the commas */
  /* 		$tags=str_replace("%20", ' ', $tags); */
  /* 		$tags=str_replace("%2C", ',', $tags); */
  /* 		$newtext .= "\nTags: (:tags {$_REQUEST['tags']} :)\n"; */
  /* 	} */
  /*   if (IsEnabled($EnableAddLinkToEnd,0)) */
  /*     $text .= $AddLinkPrefixText . "* $newtext" . $AddLinkSuffixText; */
  /*   else $text = $AddLinkPrefixText . "* $newtext" . $AddLinkSuffixText . $text; */
  /* } */

  $AddLinkUrl = (isset($_REQUEST['url']))?urldecode($_REQUEST['url']):'';
  $AddLinkTitle = (isset($_REQUEST['title']))?urldecode($_REQUEST['title']):'';
  $AddLinkSelection = (isset($_REQUEST['selected']))?urldecode($_REQUEST['selected']):'';
  $AddLinkTags = (isset($_REQUEST['tags']))?urldecode($_REQUEST['tags']):'';
  $newtext = eval($AddLinkFmt);

  @sms("newtext: ",htmlspecialchars($newtext),__FILE__,__LINE__);

  if (IsEnabled($EnabledAddLinkToEnd,0))
    $text .= $AddLinkPrefixText . $newtext . $AddLinkSuffixText;
  else
    $text = $AddLinkPrefixText . $newtext . $AddLinkSuffixText . $text;

  $action = 'edit';
  $_POST['text'] = $text;
  $OldEditHandler($pagename);
}

