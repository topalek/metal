<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'app\modules\admin\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [

                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }
	/**
	 * {@inheritdoc}
	 */
	public function init(){
		parent::init();

		// custom initialization code goes here
	}
}
