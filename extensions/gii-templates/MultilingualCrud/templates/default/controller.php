<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
<?php
    $module = Yii::app()->getModule('MultilingualCrud');
    $fPk = $module->fieldsPk;
    $keys = 'array(';
    foreach( $fPk as $pk ) {
        $keys .= "'{$pk}' => \${$pk},";
    }
    $keys .= ')';
?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='<?php echo $module->defaultLayout; ?>';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','commit'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
	 * @param integer $language Language to be displayed
	 */
	public function actionView($<?php echo implode( ', $', $fPk ); ?>)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($<?php echo implode( ', $', $fPk ); ?>)
        ) );
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		/*if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}*/

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
	 * @param string(10) $language Language of the model to be updated
	 */
	public function actionUpdate($<?php echo implode( ', $', $fPk ); ?>)
    {
        $model=$this->loadModel($<?php echo implode( ', $', $fPk ); ?>);
        $models=$this->loadFullModels($<?php echo $fPk[0]; ?>);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        /*if(isset($_POST['<?php echo $this->modelClass; ?>']))
        {
            $model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->Array));
        }*/

        $this->render('update',array(
            'models'=>$models,
            'model'=>$model,
            'modelId' => $id,
        ));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
     * @param string(10) $language Language of the model to be deleted
	 */
	public function actionDelete($<?php echo implode( ', $', $fPk ); ?>)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($<?php echo implode( ', $', $fPk ); ?>)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
     * @param string(10) $language Language of the model to be loaded
	 */
	public function loadModel($<?php echo implode( ', $', $fPk ); ?>)
    {
        $model=<?php echo $this->modelClass; ?>::model()->findByPk(<?php echo $keys; ?>);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadFullModels(<?php echo "\${$fPk[0]}"; ?>)
    {
        $models = <?php echo $this->modelClass; ?>::model()->findAllByAttributes( array('<?php echo "{$fPk[0]}"; ?>' => <?php echo "\${$fPk[0]}"; ?>) );
        if($models === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $models;
    }

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
	/**
    * Check is attribute is a range attribute
	* In this case it is neccessary to skip checking it value for empty
    * 
    * @param string $name
    * Attribute name to check in model
    * 
    * @return True if nor of the fields changed, False otherwise
    */
	private function isRangeAttribute($name)
    {
        $rules = <?php echo $this->modelClass; ?>::model()->rules();
        foreach( $rules as $rule ) {
            if( !is_array($rule) || count($rule) < 2 )
                continue;
            if( in_array('CRangeValidator', $rule ) && $rule[0] == $name ) {
                return true;
            }
        }
        return false;
    }
	
    /**
    * Check parameters list for changed elements
    * 
    * @param array $fieldsArray
    * Parameters list to check.
    * 
    * @return True if nor of the fields changed, False otherwise
    */
    private function isFieldsEmpty( $fieldsArray ) {
        foreach( $fieldsArray as $name => $value ) {
            if( in_array( $name, Yii::app()->getModule('MultilingualCrud')->fieldsPk ) ) {
                continue;
            }
            if( $value && !$this->isRangeAttribute($name) ) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Model creation/updating action
     * @param integer $modelId Optional parameter. Set param to update exact model Id, keep undefine to create a new one record
     * @param array(bool) $isChanged Array of fields states
     * @param array() $languages Array of model attributes in a different languages
     */
    public function actionCommit()
    {
        /// multilingual model primary keys fields
        $idFieldName = '<?php echo $fPk[0]; ?>';
        $langFieldName = '<?php echo $fPk[1]; ?>';
        
        /// TODO
        if( Yii::app()->getRequest()->getIsAjaxRequest() ) {
            //throw new CHttpException( 403, 'Wrong request type' );
            echo 'Wrong request type';
            Yii::app()->end();
        }
        
        /// get the model Id for updating. If there is no a such, the new record must be created
        $modelId   = Yii::app()->getRequest()->getPost('modelId');
        
        /// an array of model's fields in a different languages
        /// each of those arrays can be used as attributes for a model
        $languages = Yii::app()->getRequest()->getPost('languages');
        
        /// An array of array('language' => 'bool isChanged')
        $isChanged = Yii::app()->getRequest()->getPost('isChanged');
        
        $isChanged1 = array();
        foreach( $languages as $language => $info ) {
            $isChanged1[ $language ] = (int)!$this->isFieldsEmpty( $info );
        }
        
        /// Process changed fields
        if( !$modelId ) {
            /// create a new record
        }
        else {
            $models = <?php echo $this->modelClass; ?>::model()->findAllByAttributes( array( $idFieldName => $modelId ) );
            $isChanged2 = array();
            foreach( $models as $model ) {
                if( !isset($isChanged[$model->$langFieldName]) ) {
                    /// TODO: check for languages field in 'languages'
                    $isChanged2[$model->$langFieldName] = (int)!$this->isFieldsEmpty( $languages[$model->$langFieldName] );
                }
                else {
                    $isChanged2[ $model->$langFieldName ] = $isChanged[ $model->$langFieldName ];
                }
            }
            
            array_merge( $isChanged1, $isChanged2 );
        }
        $isChanged = $isChanged1;

        /// Process error fields for changed languages
        $errorsList = array();
        foreach( $languages as $language => $fields ) {
            if( !$isChanged[$language] ) {
                continue;
            }
            $checkModel = new <?php echo $this->modelClass; ?>();
            $checkModel->attributes = $fields;
            if( !$checkModel->validate() ) {
                $errorsList[ $language ] = $checkModel->getErrors();
            }
        }

        /// response with error code
        if( sizeof($errorsList) ) {
			Yii::log(print_r($errorsList, true));
            $this->onError( 400, 'Please check data, you entered!', $errorsList );
        }
        
        /// TODO: add transaction        
        if( isset($models) ) {
            foreach( $models as $model ) {
                $recordId = $model->$idFieldName;
                
                if( !isset($languages[$model->$langFieldName]) || !$isChanged[$model->$langFieldName] ) {
                    continue;
                }
                
                $model->attributes = $languages[$model->$langFieldName];
                unset( $languages[$model->$langFieldName] );
                if( !$model->save() ) {
                    /// throw to rollback
                }
            }
        }
        
        /// Create a new ones
        foreach( $languages as $language => $info ) {
            if( !$isChanged[$language] ) {
                continue;
            }
        
            $model = new <?php echo $this->modelClass; ?>();
            $model->attributes = $info;
            if( isset( $recordId ) ) {
                $model->$idFieldName = $recordId;
            }
            if( !$model->save() ) {
                throw new CHttpException( 450, 'Saving error!!!!!!!!!!!!!!' );
                //$this->onError( 500, 'An error occured while saving model for ' . CHtml::encode($language), array( 'An error occured while saving model for ' . CHtml::encode($language) ) );
            }
            if( !isset( $recordId ) ) {
                $recordId = $model->$idFieldName;
            }
        }
        
        /// redirect to the admin page
        $this->redirect('admin');
    }
        
    private function onError( $code, $description, $ajaxResult ) {
        if( Yii::app()->getRequest()->getIsAjaxRequest() ) {
            echo json_encode( $ajaxResult );
            Yii::app()->end();
        }
        else {
            /// TODO: reload page with error fields
            throw new CHttpException( $code, $description );
        }
    }
}
