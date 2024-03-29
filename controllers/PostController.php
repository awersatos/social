<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 30.06.19
 * Time: 16:24
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\jobs\CreateListJob;
use app\models\Post;
use app\models\PostForm;
use app\models\User;
use \yii\db\Query;

class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $id = Yii::$app->user->id;
        $last = (int)Yii::$app->request->get('last') ?? 0;
        $cache = \Yii::$app->cache;
        $page = $cache->get("page-$id-$last");
        if ($page === false) {
            Yii::$app->queue->push(new CreateListJob([
                'id' => $id,
                'last' => $last,
            ]));

            for ($i = 0; $i < 2500; $i++) {
                usleep(10000);
                $page = $cache->get("page-$id-$last");
                if ($page !== false) {
                    break;
                }
            }

        }
        $total = $cache->get("total-$id") ? $cache->get("total-$id") : 0 ;

        return $this->asJson($page ? ['posts' => $page, 'success' => true, 'total' => $total] : ['error' => 'not_page']);
    }

    public function actionMyPosts()
    {
        $posts = (new Query())
            ->select([])
            ->from('post')
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy('id DESC')
            ->all();

        return $this->render('my_posts', ['posts' => $posts]);
    }

    public function actionDelete()
    {
        $id = (int)Yii::$app->request->get('id');
        $post = Post::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        $post->delete();
        $this->updateCache(Yii::$app->user->id);


        return $this->redirect('/post/my-posts');
    }

    private function updateCache($usrId)
    {
        $user = User::findOne($usrId);
        if ($user->followers >= Yii::$app->params['followersThreshold']) {
            $posts = (new Query())
                ->select([])
                ->from('post')
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy('id DESC')
                ->limit(20)
                ->all();
            $cache = \Yii::$app->cache;
            $cache->set("posts-$usrId", $posts);
        }
    }

    public function actionCreate()
    {
        $model = new PostForm();
        if($model->load(Yii::$app->request->post())){
            $model->create();
            $this->updateCache(Yii::$app->user->id);
            return $this->redirect('/post/my-posts');
        }
        return $this->render('create', ['model' => $model]);
    }
}
