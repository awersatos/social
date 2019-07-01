<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 01.07.19
 * Time: 21:44
 */
namespace app\jobs;

use yii\queue\JobInterface;


class CreateListJob extends BaseObject implements JobInterface
{
    public $userId;
    public $lastPostId;

    public function execute($queue)
    {

    }
}
