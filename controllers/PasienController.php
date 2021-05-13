<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Pasien;
use app\models\PasienSearch;
use app\models\Agama;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class PasienController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isRm()){
                                return true;    
                            }
                            return false;
                        }
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
                    'index'=>['get'],
                    'view'=>['post'],
                ],
            ],
        ];
    }
    function actionIndex()
    {
        $searchModel = new PasienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $agama=Agama::all();
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'agama'=>$agama,
        ]);
    }
    function actionView()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model=Pasien::find()->with([
                'rawatinap'=>function($q){
                    $q->select('NO_PASIEN,TGL_MASUK,NM_RUANG,NAMA_KAMAR,NAMAKELAS,NO_BED');
                },
                'rawatpoli'=>function($q){
                    $q->select('NO_PASIEN,TANGGAL,KET,NAMADOKTER');
                }
            ])->where(['NO_PASIEN'=>sprintf('%08d',$id)])->asArray()->limit(1)->one();
            return $this->renderAjax('view',[
                'model'=>$model,
            ]);
        }
    }
}