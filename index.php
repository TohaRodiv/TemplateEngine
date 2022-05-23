<?php

require_once __DIR__ . '/vendor/autoload.php';

$templateEngine = new TemplateEngine\TemplateEngine();

$result = $templateEngine->loadTemplate('/views/ConfirmPasswordEmail.html', [
	'TOKEN' => md5(time()),
	'TIME' => '15 минут',
]);

echo $result;

echo "\n";