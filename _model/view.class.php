<?php
/**
 * @package Swiftlet
 * @copyright 2009 ElbertF http://elbertf.com
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 */

if ( !isset($model) ) die('Direct access to this file is not allowed');

/**
 * View
 * @abstract
 */
class view
{
	public
		$rootPath,
		$viewPath,
		$siteName,
		$siteCopyright,
		$siteDesigner,
		$siteDescription,
		$siteKeywords,
		$pageTitle,
		$pageDescription,
		$pageKeywords,
		$inAdmin
		;

	private
		$model,
		$contr,

		$filesLoaded = array()
		;

	/**
	 * Initialize
	 * @param object $model
	 */
	function __construct($model)
	{
		$this->model = $model;
		$this->contr = $model->contr;

		$model = $this->model;
		$contr = $this->contr;
		$view  = $this;

		$view->rootPath = $contr->absPath;
		$view->viewPath = $contr->absPath . '_view/';

		foreach ( array(
			'siteName',
			'siteCopyright',
			'siteDesigner',
			'siteDescription',
			'siteKeywords'
			) as $v )
		{
			$view->{$v} = !empty($model->{$v}) ? $model->h($model->{$v}) : '';
		}

		$view->pageTitle       = !empty($contr->pageTitle)       ? $model->h($contr->pageTitle)       : '';
		$view->pageDescription = !empty($contr->pageDescription) ? $model->h($contr->pageDescription) : $view->siteDescription;
		$view->pageKeywords    = !empty($contr->pageKeywords)    ? $model->h($contr->pageKeywords)    : $view->siteKeywords;
	}

	/*
	 * Load a view 
	 * @param $file
	 */
	function load($file)
	{
		$this->filesLoaded[] = $file;
	}

	/*
	 * Output loaded views 
	 */
	function output()
	{
		$model = $this->model;
		$contr = $this->contr;
		$view  = $this;

		foreach ( $this->filesLoaded as $file )
		{
			if ( is_file($contr->viewPath . $file) )
			{
				require($contr->viewPath . $file);
			}
			else
			{
				$model->error(FALSE, 'Missing view file `' . $contr->viewPath . $file . '`.');
			}
		}
	}

	/*
	 * Allow HTML
	 * @param string $v
	 */
	function allow_html($v)
	{
		return html_entity_decode($v, ENT_QUOTES, 'UTF-8');
	}
}
