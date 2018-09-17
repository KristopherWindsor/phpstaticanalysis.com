<?php

require '/code/template/open.html';

$request = substr($_SERVER['REQUEST_URI'], 1);

if (ctype_alnum(str_replace('-', '', $request)) && file_exists('/code/articles/' . $request . '.php')) {
    require '/code/articles/' . $request . '.php';
} else {
    require '/code/articles/splash.php';
}

require '/code/template/close.html';
