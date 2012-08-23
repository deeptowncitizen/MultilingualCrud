<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
 
$fieldsPk = Yii::app()->getModule('MultilingualCrud')->fieldsPk;

echo "<?php\n\n
Yii::import( 'MultilingualCrud.models.MlLanguage');
\$mcModule = Yii::app()->getModule('MultilingualCrud');
if( !\$mcModule ) {
    throw new CHttpException( 500, 'Multilingual Crud moduleis not found' );
}

\$langs = MlLanguage::model()->findAllByAttributes( array('is_active' => true) );\n?>";
?>

<div class="form">

<?php echo "<?php echo CHtml::beginForm( Yii::app()->controller->createUrl(\"{$this->controller}/commit\"), 'post', array( 'id' => '{$this->class2id($this->modelClass)}-multiform', 'onSubmit' => 'onPageContentSave( jQuery(\"\") ); return false;' ) ); 
if( isset(\$modelId) )
    echo CHtml::hiddenField( 'modelId',       (\$modelId) );
?>\n"; ?>

<?php

echo "<?php

function generateName(\$language, \$columnName)
{
	return \"languages[{\$language}][{\$columnName}]\";
}

function generateField(\$columnName, \$columnType, \$columnSize, \$language = 'en_US', \$value = '')
{
    if(\$column->type==='boolean')
        return CHtml::checkBox( generateName(\$language, \$columnName) );
    else if(stripos(\$columnType,'text')!==false)
        return CHtml::textArea(generateName(\$language, \$columnName), \$value, array('rows'=>6, 'cols'=>50));
    else
    {
        if(preg_match('/^(password|pass|passwd|passcode)\$/i',\$columnName))
            \$inputField='passwordField';
        else
            \$inputField='textField';

        if(\$columnType!=='string' || \$columnSize===null)
            return CHtml::\$inputField(generateName(\$language, \$columnName), \$value);
        else
        {
            if((\$size=\$maxLength=\$columnSize)>60)
                \$size=60;
            return CHtml::\$inputField(generateName(\$language, \$columnName), \$value,    array('size'=>\$size,'maxlength'=>\$maxLength));
        }
    }
}

function getContent(\$model, \$language, \$field) {
    return (\$model->language == \$language) ? \$model->\$field : '';
}
    
/// Generate translation tabs
\$tabs = array();
\$theModel = \$model;
foreach( \$langs as \$language ) {
    \$isSource = ( 0 == strcasecmp(\$language->language, Yii::app()->language) );
    if( \$isSource ) {
        \$tabs = array_reverse(\$tabs, true); 
    }
    
    \$model = 0;
    if( isset(\$models) ) {
        foreach( \$models as \$model1 ) {
            if( \$model1->language == \$language->language ) {
                \$model = \$model1;
                break;
            }
        }
        if( !\$model ) {
            \$model = \$theModel;
        }
    }
    
    \$content = '';
    \$pageId = 0;
    \$page = 0;
    
    \$content .= CHtml::hiddenField( 'languages[' . \$language->language .'][{$fieldsPk[1]}]', \$language->language );\n
    \$content .= CHtml::hiddenField( 'isChanged[' . \$language->language .']', '1' );";

    foreach( $this->tableSchema->columns as $column )
    {
        if( $column->autoIncrement )
            continue;
        if( in_array( $column->name, $fieldsPk ) )
            continue;
            
        $theSize = (int)$column->size + 0;
            
        echo "\n\t\$content .= \"<div class=\\\"row\\\">\" . \n\t\t"
            . $this->generateActiveLabel($this->modelClass,$column, $language->language) . " . \n\t\t"
            . "generateField('$column->name', '$column->dbType', {$theSize}, \$language->language, (\$model->language === \$language->language) ? \$model->{$column->name} : '' )" . " . \n\t\t"
            . "\"</div>\";";
    }
    
    echo "?>\n";
?>

<?php
    echo "<?php\n\$tabs[ \$language->title ] = array(
        'content' => \$content,
        'id' => 'multilingual-crud-editor-tab-' . strtolower(CHtml::encode(\$language->language))
    );
    if( \$isSource ) {
        \$tabs = array_reverse(\$tabs, true); 
    
    }
} ?>";

echo "<?php
/// Tabs
\$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => \$tabs,
    'htmlOptions' => array(
        /*'id' => 'admin-page-editor-tabs',
        'class' => 'admin-page-editor-tabs-class'*/
    )
)); ?>

	<div class=\"row buttons\">
		<?php echo CHtml::submitButton('Change', array('name' => 'multilingual-submit')); ?>\n
	</div>

<?php echo CHtml::endForm(); ?>\n

</div><!-- form -->";