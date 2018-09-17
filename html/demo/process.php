<?php

// This script runs the static analysis tools

require_once '/code/demo/vendor/autoload.php';

$resultsId = rand();

(new Demo\Runner())->run($_REQUEST, $resultsId);

header('Location: /demo/results/' . dechex($resultsId));

echo 'Redirecting...';
