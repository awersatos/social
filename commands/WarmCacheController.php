<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 30.06.19
 * Time: 12:32
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use \yii\db\Query;

class WarmCacheController extends Controller
{
    /**
     * This command warming Redis cache.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $threshold = \Yii::$app->params['followersThreshold'];
        $pageLimit = \Yii::$app->params['pageLimit'];
        $cache = \Yii::$app->cache;

        $premiumUsers = (new Query())
            ->select('id')
            ->from('user')
            ->where("followers >= $threshold")
            ->all();

        foreach ($premiumUsers as $user){

            $id = $user['id'];
            $posts = (new Query())
                ->select([])
                ->from('post')
                ->where(['user_id' => $id])
                ->limit($pageLimit)
                ->all();
            $cache->set("posts-$id", $posts);

        }

        echo "Cache warmed successfully!\n";
        return ExitCode::OK;
    }
}
