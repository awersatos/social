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
use app\models\User;
use yii\web\Response;
use yii\filters\VerbFilter;

class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@']]
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
        if($page === false){
            Yii::$app->queue->push(new CreateListJob([
                'id' => $id,
                'last' => $last,
            ]));

            for($i=0; $i < 250; $i++){
                usleep(100);
                $page = $cache->get("page-$id-$last");
                if($page !== false){
                    break;
                }
            }

        }
        return $this->asJson($page ? $page : ['error' => 'not_page']);
    }
}
