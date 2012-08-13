<?php
$this->breadcrumbs=array(
	'Ml Languages'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List MlLanguage', 'url'=>array('index')),
	array('label'=>'Create MlLanguage', 'url'=>array('create')),
	array('label'=>'Update MlLanguage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MlLanguage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MlLanguage', 'url'=>array('admin')),
);
?>

<h1>View MlLanguage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'language',
		'title',
		'native_title',
		'is_active',
	),
)); ?>
