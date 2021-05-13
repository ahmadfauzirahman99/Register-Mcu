<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Informasi;
use app\models\InformasiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class InformasiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','form','save','delete'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter()){
                                return true;    
                            }
                            return false;
                        }
                    ]
                ],
                'denyCallback' => function ($rule, $action){
                    $url=Yii::$app->urlManager->createUrl('/auth/index');
                    return $this->redirect($url);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'=>['get'],
                    'form'=>['post'],
                    'save'=>['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new InformasiSearch();
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
            if($id!=NULL){
                $model = $this->findModel($id);
            }else{
                $model = new Informasi;
            }
            $model->scenario="create";
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
            if($id!=NULL){
                $model = $this->findModel($id);
            }else{
                $model = new Informasi;
            }
            $model->scenario="create";
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Informasi berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Informasi gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    public function actionDelete()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = $this->findModel($id);
            if($model->delete()){
                $result=['status'=>true,'msg'=>'Informasi berhasil dihapus'];
            }else{
                $result=['status'=>false,'msg'=>'Informasi gagal dihapus'];
            }
            return $this->asJson($result);
        }
    }
    protected function findModel($id)
    {
        if (($model = Informasi::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}