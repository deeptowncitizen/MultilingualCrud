<?php
$this->breadcrumbs=array(
	'Ml Languages',
);

$this->menu=array(
	array('label'=>'Create MlLanguage', 'url'=>array('create')),
	array('label'=>'Manage MlLanguage', 'url'=>array('admin')),
);
?>

<h1>Ml Languages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
