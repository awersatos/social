<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 01.07.19
 * Time: 21:44
 */

namespace app\jobs;

use yii\queue\JobInterface;
use app\models\User;

class CreateListJob extends BaseObject implements JobInterface
{
    public $id;
    public $last;

    public function execute($queue)
    {
        $user = User::findOne($this->id);
        if(is_array($user->followed) && (count($user->followed) !=0)){

        }
    }
}
