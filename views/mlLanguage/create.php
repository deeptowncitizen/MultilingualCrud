<?php
$this->breadcrumbs=array(
	'Ml Languages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MlLanguage', 'url'=>array('index')),
	array('label'=>'Manage MlLanguage', 'url'=>array('admin')),
);
?>

<h1>Create MlLanguage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>