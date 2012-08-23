<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php

$firstField = '';
$fPk = Yii::app()->getModule('MultilingualCrud')->fieldsPk;
$keys = 'array(';
foreach( $fPk as $pk ) {
	$keys .= "'{$pk}' => \$model->{$pk},";
	if( !$firstField ) {
		$firstField = $pk;
	}
}
$keys .= ')';

echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view', {$keys}),
	'Update',
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'View <?php echo $this->modelClass; ?>', 'url'=>array('view', <?php echo "{$keys}"; ?>)),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1>Update <?php echo $this->modelClass." #<?php echo \$model->{$firstField}; ?>"; ?></h1>

<?php
echo "<?php\n
    \$ar = array();
    \$ar['model'] = \$model;
    if( isset(\$modelId) )
        \$ar['modelId'] = \$modelId;
    if( isset(\$models) )
        \$ar['models'] = \$models;

    echo \$this->renderPartial('_form', \$ar);
?>";
?>