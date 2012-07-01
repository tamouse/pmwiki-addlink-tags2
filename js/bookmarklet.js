var tags,
    u,
    a,
    d=document,
    w=window,
    e=w.getSelection,
    k=d.getSelection,
    x=d.selection,
    s = (e ? e() : (k) ? k() : (x ? x.createRange().text : 0 )),
    f = '\$PageUrl?action=addlink',
    l = d.location,
    en = encodeURIComponent;
if (s == '') s = prompt('Descriptive Text:');
tags=prompt('Enter comma-separated tags:');
u = f + '?url=' + en(l.href) +
    '&title=' + en(d.title) +
    '&selection=' + en(s) +
    '&tags=' + en(tags);
a = function () {
    if (!w.open(u, 't',
		'toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570'))
	l.href = u;
};
if (/Firefox/.test(navigator.userAgent))
    setTimeout(a, 0);
else
    a();
void(0)