<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use \yii\db\Query;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //$dataProvider = new  ArrayDataProvider([]);
        return $this->render('index'/*,['dataProvider'=> $dataProvider]*/);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionFollowing()
    {
        $followers = (new Query())
            ->select(['f.follower_id', 'u.name as user'])
            ->from('following f')
            ->innerJoin('user u', 'u.id = f.follower_id')
            ->where(['f.user_id' => Yii::$app->user->id])
            ->all();
        return $this->render('following', ['followers' => $followers]);
    }

    public function actionFollowed()
    {

        $follows = (new Query())
            ->select(['f.user_id', 'u.name as user'])
            ->from('following f')
            ->innerJoin('user u', 'u.id = f.user_id')
            ->where(['f.follower_id' => Yii::$app->user->id])
            ->all();
        return $this->render('followed', ['followers' => $follows]);
    }

    public function actionUser()
    {
        $user = User::findOne(Yii::$app->request->get('id'));
        $me = User::findOne(Yii::$app->user->id);
        $followed = unserialize($me->followed);
        if(array_search($user->id, $followed) === false){
            $status = 'u';
        } else {
            $status = 'f';
        }

        $posts = (new Query())
            ->select(['message'])
            ->from('post')
            ->where(['user_id' => $user->id])
            ->orderBy('id DESC')
            ->all();

        return $this->render('user', [
            'user' => $user,
            'posts' => $posts,
            'status' => $status
        ]);
    }

    public function actionUserFollow(){
        $user = User::findOne(Yii::$app->request->get('id'));
        $me = User::findOne(Yii::$app->user->id);
        $action = Yii::$app->request->get('action');
        $followed = unserialize($me->followed);
        if($action == 'f'){
            Yii::$app->db->createCommand()->insert('following', [
                'user_id' => $user->id,
                'follower_id' => $me->id,
            ])->execute();
            $followed[] = $user->id;
            $me->followed = serialize($followed);
            $me->save();
            $user->updateCounters(['followers' => 1]);
        } else {
            Yii::$app->db->createCommand()->delete('following', [
                'user_id' => $user->id,
                'follower_id' => $me->id,
            ])->execute();
            $key = array_search($user->id, $followed);
            unset($followed[$key]);
            $me->followed = serialize($followed);
            $me->save();
            $user->updateCounters(['followers' => -1]);
        }
        return $this->redirect('/site/user?id=' . $user->id);
    }

}
