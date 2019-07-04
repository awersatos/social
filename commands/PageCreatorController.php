<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 04.07.19
 * Time: 17:18
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use \yii\db\Query;
use app\models\User;

class PageCreatorController extends Controller
{
    /**
     * This command warming Redis cache.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public $usrId = 18;
    public $last = 0;

    public function actionIndex()
    {


        $user = User::findOne($this->usrId );
        $followed = unserialize($user->followed);
        $cache = \Yii::$app->cache;
        if (is_array($followed) && (count($followed) != 0)) {
            $result = [];
            $postIdx = [];
            foreach ($followed as $userId) {
                $posts = $cache->get("posts-$userId");
                if ($posts !== false) {
                    $author = User::findOne($userId);
                    foreach ($posts as $post) {
                        if (($this->last == 0) || (($this->last  > 0) && ($post['id'] < $this->last ))) {
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
                ->innerJoin('user u','u.id = p.user_id')
                ->where(['in', 'p.user_id', $followed])
                ->andWhere(['not in', 'p.id', $postIdx]);
            if($this->last  > 0){
                $query ->andWhere(['<', 'p.id', $this->last]);
            }
           $posts = $query
               ->orderBy('p.id DESC')
               ->limit(20)
               ->all();

            $result = array_merge($result, $posts);
            usort($result, [$this, 'cmp_function_desc']);
            $result = array_slice($result, 0, 20);
            $cache->set("page-$this->usrId-$this->last", $result, 30);
        }
        return ExitCode::OK;
    }

    private function cmp_function_desc($a, $b){
        return ($a['id'] < $b['id']);
    }

}

