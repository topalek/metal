<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email      E-mail
 * @property string $username   Имя
 * @property string $password   Пароль
 * @property int $status
 * @property string $auth_key
 * @property string $access_token
 * @property string $code
 * @property int $is_email
 * @property string $updated_at Дата оновлення
 * @property string $created_at Дата створення
 */
class User extends ActiveRecord implements IdentityInterface {

	const NOT_ACTIVE = 0;
	const ACTIVE = 1;

	public $rememberMe = true;
	public $_user = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(){
		return 'user';
	}

	public static function findIdentity($id){
		return User::findOne($id);
	}

	public static function findIdentityByAccessToken($token, $type = null){
		return User::findOne(['access_token' => $token]);
	}

	public static function findByUsername($username){
		return self::findOne(['username' => $username]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(){
		return [
			[['email', 'password'], 'required', 'on' => 'register'],
			[['username', 'auth_key', 'code', 'status', 'is_email', 'rememberMe'], 'safe', 'on' => 'registration'],
			//            [['email', 'username', 'password', 'auth_key'], 'required'],

			[['status', 'is_email'], 'integer'],
			[
				['updated_at', 'created_at', 'username', 'is_email', 'auth_key', 'status', 'code'], 'safe',
				'on' => 'register'
			],
			[['email', 'username', 'password', 'auth_key', 'access_token', 'code'], 'string', 'max' => 255],

			[['email', 'password'], 'required', 'on' => 'login'],
			[['email', 'username', 'password', 'auth_key', 'rememberMe', 'code'], 'safe', 'on' => 'login'],

		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(){
		return [
			'id'           => 'ID',
			'email'        => 'E-mail',
			'username'     => 'Имя',
			'password'     => 'Пароль',
			'status'       => 'Status',
			'auth_key'     => 'Auth Key',
			'access_token' => 'Access Token',
			'code'         => 'Code',
			'is_email'     => 'Is Email',
			'updated_at'   => 'Дата оновлення',
			'created_at'   => 'Дата створення',
		];
	}

	public function getId(){
		return $this->id;
	}

	public function getAuthKey(){
		return $this->auth_key;
	}

	public function validateAuthKey($authKey){
		return $this->auth_key === $authKey;
	}

	/**
	 * @param $password
	 *
	 * @return bool
	 */
	public function validatePassword($password){
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	public function sendConfirmLink(){
		$url    = Yii::$app->urlManager->createAbsoluteUrl([
			'site/confirm-email', 'email' => $reg->email, 'code' => $reg->code
		]);
		$link   = Html::a('Подтвердите email', $url);
		$result = Yii::$app->mailer->compose()
		                           ->setFrom(Yii::$app->params['adminEmail'])
		                           ->setTo($this->email)
		                           ->setSubject('Потвердите Email')
		                           ->setHtmlBody('<p>Для подтверждения регистрации Вам необходимо пройти по ссылке ' . $link . '</p>')
		                           ->send();

		return $result;
	}

	/**
	 * @return bool
	 */
	public function login(){
		$this->scenario = 'login';

		if ($this->validate()){
			if ($this->rememberMe){
				$user = $this->getUser();
				if ( ! $user){
					return false;
				}
				$user->generateAuthKey();
				$user->save();
			}

			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		}

		return false;
	}

	/**
	 * @return User|bool|null
	 */
	public function getUser(){
		if ($this->_user === false){
			$this->_user = User::findByEmail($this->email);
		}

		return $this->_user;
	}

	public static function findByEmail($email){
		return self::findOne(['email' => $email]);
	}

	public function generateAuthKey(){
		$this->auth_key = Yii::$app->security->generateRandomString();
	}
}
