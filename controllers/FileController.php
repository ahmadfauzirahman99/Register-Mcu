<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Setting;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class FileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['pedoman-pemeriksaan'],
                        'allow' => true,
                        'roles' => ['@','?'],
                    ],
                ],
                'denyCallback' => function ($rule, $action)
                {
                    $url=Yii::$app->urlManager->createUrl('/auth/index');
                    return $this->redirect($url);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'pedoman-pemeriksaan'=>['get'],
                ],
            ],
        ];
    }
    function actionPedomanPemeriksaan($q)
    {
        $setting=Setting::find()->where(['set_kode'=>$q==1 ? 'pedoman_pemeriksaan_umum' : 'pedoman_pemeriksaan_instansi'])->asArray()->limit(1)->one();
        if($setting!=NULL){
            if(file_exists(Yii::$app->params['storage_app'].$setting['set_value']) && is_file(Yii::$app->params['storage_app'].$setting['set_value'])){
				return Yii::$app->response->sendFile(Yii::$app->params['storage_app'].$setting['set_value'], $setting['set_value'], ['inline'=>true]);
			}
        }
        throw new NotFoundHttpException('File not found !');
    }
}