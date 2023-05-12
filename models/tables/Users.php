<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string|null $password
 * @property string|null $authKey
 * @property string|null $accessToken
 */
class Users extends \yii\db\ActiveRecord
{
    public $admin_id = 100;
    public $admin_name = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'unique'],
            [['username'], 'string', 'max' => 20],
            [['password', 'accessToken'], 'string', 'max' => 255],
            [['authKey'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
        ];
    }

    public function isAdmin()
    {
        if (Yii::$app->user->identity->getId() === $this->admin_id){
            return true;
        }else{
            return false;
        }
    }
}
