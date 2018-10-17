<?php

namespace app\common;

use yii\web\Controller;

class BaseController extends Controller {
	public static function generateSlug($string){
		return Inflector::slug($string, '_');
	}

	public function setMeta($title = null, $description = null, $keywords = null){
		$this->view->title = $title;
		$this->view->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
		$this->view->registerMetaTag(['name' => 'description', 'content' => $description]);
	}
}
