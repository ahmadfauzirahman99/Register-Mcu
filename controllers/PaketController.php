<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Tarif;
use app\models\Paket;
use app\models\PaketTindakan;
use app\models\PaketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class PaketController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','form','save','view','delete','tindakan-form','tindakan-list','tindakan-save','tindakan-delete'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter()){
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
                    'index' => ['get'],
                    'form'=>['post'],
                    'save'=>['post'],
                    'view'=>['get'],
                    'tindakan-form'=>['post'],
                    'tindakan-list'=>['post'],
                    'tindakan-save'=>['post'],
                    'tindakan-delete'=>['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new PaketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
        $model= $this->findModel($id);
        $tindakan=PaketTindakan::find()->where(['kode_paket'=>$model->kode])->asArray()->all();
        return $this->render('view', [
            'model' => $model,
            'tindakan'=>$tindakan,
        ]);
    }
    function actionForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = $id!=NULL ? Paket::findOne($id) : new Paket();
            return $this->renderAjax('form',[
                'model'=>$model
            ]);
        }
    }
    function actionSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = $id!=NULL ? Paket::findOne($id) : new Paket();
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Paket berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Paket gagal disimpan'];
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
            $model = Paket::findOne($id);
            if($model->delete()){
                $result=['status'=>true,'msg'=>'Paket berhasil dihapus'];
            }else{
                $result=['status'=>false,'msg'=>'Paket gagal dihapus'];
            }
            return $this->asJson($result);
        }
    }
    protected function findModel($id)
    {
        if (($model = Paket::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    function actionTindakanForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $p=$req->post('p');
            $id=$req->post('id');
            $model = $id!=NULL ? PaketTindakan::findOne($id) : new PaketTindakan();
            $p!=NULL ? $model->kode_paket=$p : NULL;
            return $this->renderAjax('tindakan_form',[
                'model'=>$model
            ]);
        }
    }
    function actionTindakanList()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $q=$req->post('q');
            $tarif=Tarif::find()->select('KodeJenis as id, Ket1 as text,(Js_Rs+Js_MedRs+Js_MedL+Js_MedTL+Js_KSO) as harga')->groupBy('KodeJenis, Ket1,Js_Rs,Js_MedRs,Js_MedL,Js_MedTL,Js_KSO')->where(['like','Ket1',$q])->limit(20)->asArray()->all();
            return $this->asJson(['results'=>$tarif]);
        }
    }
    function actionTindakanSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = $id!=NULL ? PaketTindakan::findOne($id) : new PaketTindakan();
            $model->kode_unit=3902;
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Tindakan berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Tindakan gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    public function actionTindakanDelete()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = PaketTindakan::findOne($id);
            if($model->delete()){
                Yii::$app->session->setFlash('true','Tindakan berhasil dihapus');
                $result=['status'=>true];
            }else{
                $result=['status'=>false,'msg'=>'Tindakan gagal dihapus'];
            }
            return $this->asJson($result);
        }
    }
}