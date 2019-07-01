<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 30.06.19
 * Time: 15:15
 */

namespace app\models;

use yii\db\ActiveRecord;
class Post extends ActiveRecord
{
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {

        return '{{post}}';
    }


}
