<?php

namespace TemplateEngine;

/**
 * Простой HTML/PHP шаблонизатор
 */
class TemplateEngine
{
	/**
	 * @var string путь до папки с шаблонами
	 */
	protected $templateDir;
	/**
	 * @var string|null имя базового шаблона
	 */
	protected $baseTemplateName;
	/**
	 * @var array доп. параметры базового шаблона
	 */
	protected $baseTemplateParams;


	/**
	 * @param string $templateDir путь до папки с шаблонами
	 */
	public function __construct(string $templateDir)
	{
		$this->templateDir = $templateDir;
		/**
		 * По умолчанию работаем без базового шаблона, поэтому $this->baseTemplateName = null
		 */
		$this->baseTemplateName = null;
		$this->baseTemplateParams = [];
	}
	/**
	 * @param string|null $templateName имя базового шаблона
	 */
	public function setBaseTemplateName(string|null $templateName)
	{
		$this->baseTemplateName = $templateName;
	}
	/**
	 * @param array $params доп. параметры базового шаблона
	 */
	public function setBaseTemplateParams(array $params)
	{
		$this->baseTemplateParams = $params;
	}
	/**
	 * Возвращает обработанный шаблон
	 * 
	 * @param string $templateName имя файла шаблона (можно путь указать)
	 * @param array $params параметры, передаваемые в шаблон
	 * 
	 * @throws Exception
	 * 
	 * @return string обработанный шаблон
	 */
	protected function _loadTemplate(string $templateName, array $_params = [])
	{
		$_templatePath = $this->templateDir . $templateName;

		if (!is_file($_templatePath)) {
			throw new \Exception("Шаблон не найден по указаному пути: $_templatePath");
		}

		$params = new class {
			public function __set($name, $value) {
				$this->{$name} = $value;
			}
		};

		foreach ($_params as $paramName => $paramValue) {
			$params->{$paramName} = $paramValue;
		}

		unset($_params);
		unset($templateName);
		unset($paramName);
		unset($paramValue);

		ob_start();
		include $_templatePath;
		return ob_get_clean();
	}
	/**
	 * Возвращает обработанный шаблон вместе с базовым шаблонов. В базовом шаблоне обязательно должна быть переменная $content
	 * 
	 * @param string $templateName имя файла шаблона (можно путь указать)
	 * @param array $params параметры, передаваемые в шаблон
	 * 
	 * @throws Exception
	 * 
	 * @return string
	 */
	public function loadTemplate(string $templateName, array $params = [])
	{
		$content = $this->_loadTemplate($templateName, $params);

		if (!is_null($this->baseTemplateName)) {
			$baseTemplateParams = array_merge(
				[
					'content' => $content,
				],
				$this->baseTemplateParams,
			);
			
			return $this->_loadTemplate($this->baseTemplateName, $baseTemplateParams);
		} else {
			return $content;
		}
	}
}
