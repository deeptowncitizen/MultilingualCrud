<?php
/**
 * MultilingualActiveRecordBehavior class file.
 *
 * @author Dmitry Reshetnik <reshetnikd@gmail.com>
 * @link http://all-of.me/yii-multilingual-crud/
 * @copyright Copyright &copy; 2012
 * @license GPL v3
 */

/**
 * Multilingual models helper
 * 
 * Now you have configured models, CRUD. But you need fetch data from DB. So at this moment i can suggest you just 2 methods from behavior, you connected the model:
 * - get exact field by PK:
 * Model::model()->ml->localePk(<id>, <language>)->find();
 * - get all translations of the model:
 * Model::model()->ml->locale(<language>)->findAll();
 * - get filter exact language by a custom field:
 * Model::model()->ml->locale(<language>)->findAllByAttributes( array() );
 * As you can see it is very easy to use behavior in your routines. Exception is searching for composite primary key, where you should use behavior method (first example)
 *
 * @author Dmitry Reshetnik <reshetnikd@gmail.com>
 * @version 0.1
 */
class MultilingualActiveRecordBehavior extends CActiveRecordBehavior
{
    /**
    * Use this search() filter instead of Model::search() for future compatibility
    * 
    */
    public function search() {
        $owner = $this->getOwner();
        $criteria = $owner->search()->getCriteria();

        /*$owner->getDbCriteria()->mergeWith(
        ); */
        
        $owner->search()->setCriteria( $criteria );
        return $owner->search();
    }
    
    /**
    * Add condition to fetch just passed language
    * 
    * @param string $language
    * Language to filter. Use null to use a current language
    */
    public function locale($language = null) {
        $owner = $this->getOwner();
        $languageField = Yii::app()->getModule('MultilingualCrud')->fieldsPk[1];
        if( !$language ) {
            $language = Yii::app()->language;
        }
        
        $owner->getDbCriteria()->mergeWith(
            array(
                'condition' => "{$languageField}='{$language}'"
            )
        );
        return $owner;
    }
    
    /**
    * Find record by composite primary key (id, language)
    * 
    * @param integer $id
    * Model record id
    * @param string $language
    * Language to find. Use null to find a current language record
    */
    public function localePk($id, $language = null) {
        $owner = $this->getOwner();
        $languageField1 = Yii::app()->getModule('MultilingualCrud')->fieldsPk[0];
        $languageField2 = Yii::app()->getModule('MultilingualCrud')->fieldsPk[1];
        if( !$language ) {
            $language = Yii::app()->language;
        }
        
        $owner->getDbCriteria()->mergeWith(
            array(
                'condition' => "{$languageField1}='{$id}'"
            )
        );
        
        $owner->getDbCriteria()->mergeWith(
            array(
                'condition' => "{$languageField2}='{$language}'"
            )
        );
        
        return $owner;
    }
}