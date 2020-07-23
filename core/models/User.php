<?php

namespace app\models;

use app\modules\admin\models\AuthAssignment;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
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
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $code
 * @property int $is_email
 * @property string $updated_at Дата оновлення
 * @property string $created_at Дата створення
 *
 * @property AuthAssignment $assignment
 */
class User extends ActiveRecord implements IdentityInterface
{

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;
    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";

    public $rememberMe = true;
    public $_user = false;
    public $role;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function roleList()
    {
        return [
            self::ROLE_USER  => "Пользователь",
            self::ROLE_ADMIN => "Администратор",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'on' => 'registration'],
            [
                ['username', 'auth_key', 'status', 'password_reset_token', 'verification_token', 'role', 'rememberMe'],
                'safe',
                'on' => 'registration'
            ],
            ['email', 'unique', 'message' => 'Еmail уже зарегистрован'],
            ['username', 'unique', 'message' => 'Пользователь уже зарегистрован'],

            [['status'], 'integer'],
            [
                ['updated_at', 'created_at', 'username', 'auth_key', 'status'],
                'safe',
                'on' => 'registration',
            ],
            [['email', 'username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 255],

            [['email', 'password'], 'required', 'on' => 'login'],
            [
                [
                    'email',
                    'username',
                    'password',
                    'auth_key',
                    'password_reset_token',
                    'verification_token',
                    'rememberMe',
                ],
                'safe',
                'on' => 'login'
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'email'        => 'E-mail',
            'username'     => 'Логин',
            'password'     => 'Пароль',
            'status'       => 'Статус',
            'statusName'   => 'Статус',
            'auth_key'     => 'Auth Key',
            'access_token' => 'Access Token',
            'code'         => 'Code',
            'updated_at'   => 'Дата оновлення',
            'created_at'   => 'Дата створення',
            'role'         => 'Роль',
            'value'        => 'Роль',
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function sendConfirmLink()
    {
        //        $url = Yii::$app->urlManager->createAbsoluteUrl([
        //            'site/confirm-email', 'email' => $reg->email, 'code' => $reg->code,
        //        ]);
        //        $link = Html::a('Подтвердите email', $url);
        //        $result = Yii::$app->mailer->compose()
        //            ->setFrom(Yii::$app->params['adminEmail'])
        //            ->setTo($this->email)
        //            ->setSubject('Потвердите Email')
        //            ->setHtmlBody('<p>Для подтверждения регистрации Вам необходимо пройти по ссылке ' . $link . '</p>')
        //            ->send();
        //
        //        return $result;
    }

    /**
     * @return bool
     */
    public function login()
    {
        $this->scenario = 'login';

        if ($this->validate()) {
            if ($this->rememberMe) {
                $user = $this->getUser();
                if ( ! $user) {
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
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue($this->getStatusList(), $this->status);
    }

    public function getStatusList()
    {
        return [
            self::NOT_ACTIVE => "Не активен",
            self::ACTIVE     => "Активен",
        ];
    }

    public function getRoleList()
    {
        return [
            'admin' => "Администратор",
            'user'  => "Пользователь",
        ];
    }

    public function getAssignment()
    {
        return AuthAssignment::findOne(['user_id' => $this->id]);
    }

    public function getValue()
    {
        return $this->assignment->item_name;
    }

    private function assignRole()
    {
        $userRole = Yii::$app->authManager->getRole($this->role);
        Yii::$app->authManager->assign($userRole, $this->id);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->assignRole();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function generatePasswordHash()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);
    }

    public function beforeSave($insert)
    {
        $this->generateAuthKey();

        //$this->generatePasswordHash();

        return parent::beforeSave($insert);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        return static::findOne(
            [
                'password_reset_token' => $token,
                'status'               => self::ACTIVE,
            ]
        );
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne(
            [
                'verification_token' => $token,
                'status'             => self::NOT_ACTIVE
            ]
        );
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
