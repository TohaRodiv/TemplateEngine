<?php

namespace TemplateEngine;

/**
 * Простой HTML шаблонизатор. В базовом варианте использются переменные в таком синтаксисе #VAR_NAME#
 */
class TemplateEngineV1
{
	/**
	 * @var string $templateDir
	 */
	protected $templateDir;
	/**
	 * @var array $chars
	 */
	protected $chars;
	/**
	 * 
	 * @param array $options
	 * ```
	 * $options['template_dir'] = 'Путь до папки с шаблонами;
	 * $options['chars'] = ['#', '#'];
	 * ```
	 */
	public function __construct(array $options = [])
	{
		$this->templateDir = !empty($options['template_dir']) ? $options['template_dir'] : __DIR__ . '/templates/';
		$this->chars = !empty($options['chars']) ? $options['chars'] : ['#', '#'];
	}
	/**
	 * Возвращает обработанный шаблон
	 * 
	 * @param string $templateName имя файла шаблона (можно путь указать)
	 * @param array $params переменные, передаваемые в шаблон
	 * 
	 * @throws Exception
	 * 
	 * @return string обработанный шаблон
	 */
	public function loadWithoutBaseTemplate(string $templateName, array $params)
	{
		$templatePath = $this->templateDir . $templateName;

		if (!file_exists($templatePath)) {
			throw new \Exception("Шаблон не найден по указаному пути: $templatePath");
		}

		$content = file_get_contents($templatePath);

		foreach ($params as $paramName => $paramValue) {
			$content = str_replace("{$this->chars[0]}$paramName{$this->chars[1]}", $paramValue, $content);
		}

		return $content;
	}
	/**
	 * Возвращает обработанный шаблон вместе с базовым шаблонов. В базовом шаблоне обязательно должна быть переменная CONTENT
	 * 
	 * @param string $templateName имя файла шаблона (можно путь указать)
	 * @param array $params переменные, передаваемые в шаблон
	 * 
	 * @throws Exception
	 * 
	 * @return string
	 */
	public function loadTemplate(string $templateName, array $params, string $baseTemplateName = 'base.html')
	{
		$content = $this->loadWithoutBaseTemplate($templateName, $params);
		return $this->loadWithoutBaseTemplate($baseTemplateName, [
			'CONTENT' => $content,
		]);
	}
}
