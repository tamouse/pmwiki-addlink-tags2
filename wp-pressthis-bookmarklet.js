//javascript:
var d = document,
w = window,
e = w.getSelection,
k = d.getSelection,
x = d.selection,
s = (e ? e() : (k) ? k() : (x ? x.createRange().text : 0)),
f = 'http://www.tamaratemple.com/wp-admin/press-this.php',
l = d.location,
e = encodeURIComponent,
u = f + '?u=' + e(l.href) + '&t=' + e(d.title) + '&s=' + e(s) + '&v=4';
a = function () {
    if (!w.open(u, 't', 'toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570')) l.href = u;
};
if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0);
else a();
void(0)
