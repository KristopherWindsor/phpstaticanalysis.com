<?php

require '/code/template/open.html';

$resultsId = hexdec(str_replace('/demo-results/', '', $_SERVER['REQUEST_URI']));
$filename = __DIR__ . '/' . $resultsId . '/results.html';

if (file_exists($filename)) {
	readfile($filename);
} else {
	echo 'Link is expired or invalid.';
}

require '/code/template/close.html';
