<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\Country;

class TestController extends Controller
{

    public function actionIndex()
    {
        return $this->render("index");
    }

    public function actionTestJs()
    {
        return $this->render("test-js");
    }

    public function actionEntry()
    {
        $model = new EntryForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // 验证 $model 收到的数据

            // 做些有意义的事 ...

            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // 无论是初始化显示还是数据验证错误
            return $this->render('entry', ['model' => $model]);
        }
    }

    public function actionCountry()
    {
        $countries = Country::find()->orderBy('name')->all();
        $query = Country::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('country', [
            'countries' => $countries,
            'pagination' => $pagination,
        ]);
    }

    public function actionSuggestion()
    {
        return $this->render('suggestion', []);
    }

    public function testMigration()
    {

    }

    public function testNamespace()
    {
    }
}
