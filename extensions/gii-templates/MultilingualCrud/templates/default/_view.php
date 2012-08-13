<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="view">

<?php
$firstField = '';
$fPk = Yii::app()->getModule('MultilingualCrud')->fieldsPk;
$keys = '';
foreach( $fPk as $pk ) {
	$keys .= "'{$pk}' => \$data->{$pk},";
	if( !$firstField ) {
		$firstField = $pk;
	}
}

echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$firstField}')); ?>:</b>\n";
echo "\t<?php echo CHtml::link(CHtml::encode(\$data->{$firstField}), array('view', $keys)); ?>\n\t<br />\n\n";
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
	if(++$count==7)
		echo "\t<?php /*\n";
	echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
	echo "\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
}
if($count>=7)
	echo "\t*/ ?>\n";
?>

</div>