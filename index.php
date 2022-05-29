<?php

require_once __DIR__ . '/vendor/autoload.php';

use TemplateEngine\TemplateEngine;

$templateEngine = new TemplateEngine(__DIR__ . '/src/templates/');
$templateEngine->setBaseTemplateName('base.php');
$templateEngine->setBaseTemplateParams([
	'lang' => 'ru',
	'charset' => 'UTF-8',
]);

class User {
	public $name;
	public $email;
	public $phone;
}

$user = new User();
$user->name = 'Иван Иванович';
$user->email = 'ivan@mail.com';
$user->phone = '8 (999) 999-99-99';

$template = $templateEngine->loadTemplate('views/template.php', [
	'user' => $user,
]);

echo $template;

echo "\n";