<?php

use yii\helpers\VarDumper;

/**
 * Created by topalek
 * Date: 28.09.2018
 * Time: 10:33
 */

function dump($data, $num = 10, $highlight = true){
	VarDumper::dump($data, $num, $highlight);
}

function dd($data){
	dump($data);
	die;
}

function uploadsDirPath(){
	return '/' . uploadsDirName() . '/';
}

function uploadsDirName(){
	return 'uploads';
}

function getBaseUploadsPath(){
	return Yii::getAlias('@webroot') . "/" . uploadsDirName();
}

function getBaseUploadsUrl(){
	return Yii::getAlias('@uploads');
}