<?php

class MultilingualCrudModule extends CWebModule
{
    /**
    * Model composite primary key
    * Note: First - id field, second - language field
    * 
    * @var array
    */
    public $fieldsPk = array('id', 'language');
    
    /**
    * Default layout name for generated controller
    * 
    * @var string
    */
    public $defaultLayout = '//layouts/column2';
    
    public $defaultController = 'MlLanguage';
    
	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			'MultilingualCrud.models.*',
            'MultilingualCrud.components.*',
		));
	}
}
