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
use yii\db\Query;
use yii\base\BaseObject;

class CreateListJob extends BaseObject implements JobInterface
{
    public $id;
    public $last;

    public function execute($queue)
    {

        $user = User::findOne($this->id);
        $followed = unserialize($user->followed);
        $cache = \Yii::$app->cache;
        if (is_array($followed) && (count($followed) != 0)) {
            $result = [];
            $postIdx = [];
            if($this->last == 0){
                $total = (new Query())
                    ->from('post p')
                    ->innerJoin('user u', 'u.id = p.user_id')
                    ->where(['in', 'p.user_id', $followed])
                    ->count();
                $cache->set("total-$this->id", $total, 45);
            }
            foreach ($followed as $userId) {
                $posts = $cache->get("posts-$userId");
                if ($posts !== false) {
                    $author = User::findOne($userId);
                    foreach ($posts as $post) {
                        if (($this->last == 0) || (($this->last > 0) && ($post['id'] < $this->last))) {
                            $post['user'] = $author->name;
                            $result[] = $post;
                            $postIdx[] = $post['id'];
                        }
                    }
                }
            }
            $query = (new Query())
                ->select(['p.*', 'u.name as user'])
                ->from('post p')
                ->innerJoin('user u', 'u.id = p.user_id')
                ->where(['in', 'p.user_id', $followed])
                ->andWhere(['not in', 'p.id', $postIdx]);
            if ($this->last > 0) {
                $query->andWhere(['<', 'p.id', $this->last]);
            }
            $posts = $query
                ->orderBy('p.id DESC')
                ->limit(20)
                ->all();

            $result = array_merge($result, $posts);
            usort($result, [$this, 'cmp_function_desc']);
            $result = array_slice($result, 0, 20);
            $cache->set("page-$this->id-$this->last", $result, 30);

        }
    }

    private function cmp_function_desc($a, $b)
    {
        return ($a['id'] < $b['id']);
    }
}
