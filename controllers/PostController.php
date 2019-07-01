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
use yii\web\Response;
use yii\filters\VerbFilter;

class PostController extends Controller
{
    public function actionIndex()

    {
        return $this->render('post');
    }
}
