<?php

/**
 * Test the _al_t_fix_encoding function
 */

define('PmWiki',true); // to ensure recipe will load
function SDV($a,$b) {} // mock SDV function
function Markup($a,$b,$c,$d) {} // mock Markup function

require('../cookbook/addlink-tags2.php');
$testdata1 = file_get_contents('testdata1.txt');
printf("Testdata 1 before call: %s\n",$testdata1);
$result1 = _al_t_fix_encoding($testdata1);
printf("Testdata 1 after call: %s\n",$result1);
