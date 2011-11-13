Addlink-Tags2.Php - Pmwiki Recipe
=================================

The original addlink.php recipe was useful, but it lacked the ability
to tag new links and collect a selection. The kob-addlink recipe
improved on this greatly, but was specialized for the kob journaling
system. addlink-tags2.php is meant to bridge the gap between these.

Originally, addlink-tags.php was written, but it lacked any sort of
customizability, and had a few errors. Also, it necessitated having
two different bookmarklets, one for IE, and one for everyone else.

Addlink-tag2 goes quite a bit further. Its features include:
1. The ability to select text to be included in the bookmark.
2. The ability to add a set of tags to the bookmark.
3. The ability to configure the format of the bookmark entries on the
   catching page.
4. The catching page edit opens in a new tab or window, rather than
   changing the location of the current tab/window. This is so much
   better because you can pop back to the sending tab/window if need
   be without losing your edit.
   
Installing Addlink-Tags2.Php
----------------------------

The installation is largely the same, although there are some new
configuration options.

Install the script by copying the it to the `cookbook/` directory
and adding the following to the `local/config.php` configuration file:

    ## Enable the AddLink Bookmarklet recipe.
    if ($action == 'edit' || $action == 'browse' || $action == 'addlink') {
      include_once("$FarmD/cookbook/addlink-tags.php");
    }

Configuration options include:

    $EnableAddLinkToEnd - if set to 1, links will be appended to the
                          page text, otherwise they will be added to
                          the top. 

    $AddLinkFmt - a format specification for how you want the captured
                  link to appear on the capture page.
		  
		  The default for this variable is:
		  
                  "* [[\$AddLinkTitle -> \$AddLinkUrl]]\n->\$AddLinkSelection\n->Tags: (:tags \$AddLinkTags, bookmark:)\n-->Posted: \$AddLinkTime\n"

                  $AddLinkTitle - the title of the linked page
		  $AddLinkUrl - the url of the linked page
		  $AddLinkSelection - the selected text
		  $AddLinkTags - tags given when clipping
		  $AddLinkTime - time link was sent

    $AddLinkPrefixText - text that should appear before the link
    $AddLinkSuffixText - text that should appear after the link
    
Creating a capture page
-----------------------

To create a capture page, simply put the bookmarklet markup on the
page somewhere: `(:addlink:)`. When you save the page, drag the
bookmarklet created to your bookmark toolbar to save and activate
it. Once you've done this for your various browsers, there is no need
to keep the `(:addlink:)` markup in the page, although you may want to
so you can easily add the bookmarklet for new browsers.

Using the Bookmarklet to Capture Web Pages
------------------------------------------

Whenever you want to bookmark a page, you can simply click on the
bookmarklet when you are viewing the page. You may optionally select
some text from the page to be included, and you will be prompted for
tags to give the link.

You will then be shown a new window/tab with the landing page in edit
mode with the new links either at the top (default) or the bottom (set
`$EnableAddLinkToEnd` to 1 in `local/config.php`). Your will be
formatted as either the default or whatever format you've specified.

The landing page must always be editable by anyone attempting to save
a link. This usually means that page must be wide open to edits from
everyone. If that concerns you, consider installing a captcha recipe.

    
    


