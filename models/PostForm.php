<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 05.07.19
 * Time: 0:18
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Post;

class PostForm extends Model
{
    public $message;

    public function rules()
    {
        return [
            [['message'], 'string', 'max' => 250 ]
        ];
    }

    public function create()
    {
        $post = new Post();
        $post->user_id = Yii::$app->user->id;
        $post->timestamp = time();
        $post->message = $this->message;
        $post->save();
    }

}
