//  $mozlink = "<a href=\"javascript:selection=document.getSelection();if(!document.getSelection())selection=prompt('Text:');tags=prompt('Enter comma-separated tags:');t=document.title;t=t.replace('|','-');document.location.href='\$PageUrl?action=addlink&url='+encodeURIComponent(document.location.href)+'&selected='+encodeURIComponent(selection)+'&title='+encodeURIComponent(t)+'&tags='+encodeURIComponent(tags+', bookmark')\" title=\"send to \$WikiTitle.\">send to \$WikiTitle</a>";

//javascript:
selection = document.getSelection();
if (!document.getSelection()) selection = prompt('Text:');
tags = prompt('Enter comma-separated tags:');
t = document.title;
t = t.replace('|', '-');
document.location.href = '\$PageUrl?action=addlink&url=' + encodeURIComponent(document.location.href) + '&selected=' + encodeURIComponent(selection) + '&title=' + encodeURIComponent(t) + '&tags=' + encodeURIComponent(tags + ', bookmark')


