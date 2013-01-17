MultilingualCrud 0.1
=================

Yii Multilingual CRUD generator
-----------------------------------------------------


WHY?
-----------------------------------------------------
Did you have some discomfort, creating multilingual sites and Models?
I didn't find any elegant interface for solving this problem.


SOLVING THE PROBLEM
-----------------------------------------------------
So, what i need? I'd like to create automatically CRUD for any model with multilingual support.
Create model of any structure and create automatically CRUD for managing this model with any list of languages.
I don't need any additional tables for translations and out-of-the-box generator for any model structure.
Certainly, there will be some constraints to make it workable:
- you need to make an additional field - varchar(10) 'language' to keep language in
- change primary_key(id) to composite primary_key(id, language)
- connect special multilingual behavior for more efficiency usage
And that's all. After this you can:
- automatically generate CRUD for any multilingual CRUD
- easily manipulate your model's data in your routines


INSTALLATION
-----------------------------------------------------
Unpack module files to your application.module path.
Configurate config/main.php:
- add gii generator additional templates path
'modules'=>array(
        ...
        'gii'=>array(
			...
            'generatorPaths'=>array(
                'application.modules.MultilingualCrud.extensions.gii-templates'
            ),
			...
		),
		...
		'MultilingualCrud' => array(
			'defaultLayout' => 'application.custom.layout', /// you can set default layout for your controller here, or //layouts/column2 will be used
			'fieldsPk' => array('id', 'language'), /// you can set composite key fields names (not tested yet). It is better to keep default. Chabge before generating
		),
...
configure your current language in config
...
'language'=>'en_us',
...

- configure your exist model:
-- add composite key ('id', 'language')
-- add custom multilingual behavior to your model
....
public function behaviors()
    {
        return array(
			...
            'ml' => array( /// ml is neccessary name
                'class' => 'application.modules.MultilingualCrud.components.MultilingualActiveRecordBehavior'
            )
        );    
    }
....

- manage your languages using predefined module controller
go 'MultilingualCrud/' and admin your languages list.
You can easily disable/enable exist languages to provide access for translations in your models.

- go to the GII and create CRUD, using MultilingualCrud generator
That's all.

Now you can access your CRUD like always:
<controller>/admin
<controller>/create
<controller>/index
view, delete, update, etc.


USAGE EXAMPLES
-----------------------------------------------------
Now you have configured models, CRUD. But you need fetch data from DB. So at this moment i can suggest you just 2 methods from behavior, you connected the model:
- get exact field by PK:
Model::model()->ml->localePk(<id>, <language>)->find();
- get all translations of the model:
Model::model()->ml->locale(<language>)->findAll();
- get filter exact language by a custom field:
Model::model()->ml->locale(<language>)->findAllByAttributes( array() );
As you can see it is very easy to use behavior in your routines. Exception is searching for composite primary key, where you should use behavior method (first example)


If you need to check availability of some language in your routines, you can use built-in method:
$isEnabled = Yii::app()->getModule('MultilungualCrud')->isLanguageAvailable('en_US');

CONTACT AUTHOR or SUGGEST a FEATURE
-----------------------------------------------------
author: Reshetnik Dmitry
email: reshetnikd@gmail.com
website: http://all-of.me/yii-multilingual-crud/