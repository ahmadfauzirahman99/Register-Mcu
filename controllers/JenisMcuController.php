<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\JenisMcu;
use app\models\JenisMcuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class JenisMcuController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','form','save'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter()){
                                return true;    
                            }
                            return false;
                        }
                    ]
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
                    'form'=>['post'],
                    'save'=>['post']
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new JenisMcuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    function actionForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = JenisMcu::findOne($id);
            return $this->renderAjax('form',[
                'model'=>$model,
            ]);
        }
    }
    function actionSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = JenisMcu::findOne($id);
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Status berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Status gagal disimpan'];
                }
            }else{
                $result=['status'=>true,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    protected function findModel($id)
    {
        if (($model = JenisMcu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}