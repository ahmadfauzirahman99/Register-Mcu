<?php
namespace app\controllers;
use Yii;
use app\models\User;
use app\models\UserDaftar;
use app\models\UserSearch;
use app\models\UserDaftarSearch;
use app\models\JenisMcu;
use app\models\KategoriKuisioner;
use app\widgets\App;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class AdminController extends Controller
{
    public $status_marital=['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda'];
    public $pendidikan=[1=>'Tidak Sekolah','TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3'];
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['peserta-list','peserta-detail','peserta-status-form','peserta-status-save','new-peserta-check','get-ktp','peserta-detail-riwayat'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter() || App::isRm()){
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
                    'peserta-list'=>['get'],
                    'peserta-detail'=>['get'],
                    'peserta-status-form'=>['post'],
                    'peserta-status-save'=>['post'],
                    'new-peserta-check'=>['post'],
                    'get-ktp'=>['get'],
                    'peserta-detail-riwayat'=>['get'],
                ],
            ],
        ];
    }
    function actionPesertaList()
    {
        $searchModel = new UserDaftarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('peserta_list',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    function actionPesertaDetail($id)
    {
        $model = UserDaftar::find()->with(['agama','pekerjaan'])->where(['ud_id'=>$id])->asArray()->limit(1)->one();
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchAsRiwayat(Yii::$app->request->queryParams,$model['ud_nik']);
        $jenis_mcu=JenisMcu::find()->asArray()->all();
        return $this->render('peserta_detail',[
            'model'=>$model,
            'status_marital'=>$this->status_marital,
            'pendidikan'=>$this->pendidikan,
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'jenis_mcu'=>$jenis_mcu,
        ]);
    }
    function actionPesertaDetailRiwayat($id,$user)
    {
        $model = User::find()->where(['u_id'=>$id])->with(['agama','pekerjaan','kuisionerbiodata'])->asArray()->limit(1)->one();
        $status_marital=['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda'];
        $kedudukan_keluarga=['kepala keluarga'=>'Kepala Keluarga','anak'=>'Anak','istri'=>'Istri'];
        $pendidikan=[1=>'Tidak Sekolah','TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3'];
        $query_kategori=KategoriKuisioner::find()->where(['kk_status'=>'1']);
        if($model['u_jkel']=='L'){
            $query_kategori->andWhere('kategori_kuisioner.kk_id != 3');
        }
        if($model['u_debitur_id']!='0129'){
            $query_kategori->andWhere('kategori_kuisioner.kk_id != 5');
        }
        $kategori=$query_kategori->joinWith([
            'kuisioner'=>function($q) use($model){
                $q->joinWith([
                    'userkuisioner'=>function($q) use($model){
                        $q->andWhere(['u_id'=>$model['u_id']]);
                    }
                ],false);
            }
        ],false)->select('kategori_kuisioner.kk_id,kategori_kuisioner.kk_nama_indo')->asArray()->all();
        return $this->render('peserta_detail_riwayat',[
            'user'=>$user,
            'model'=>$model,
            'status_marital'=>$status_marital,
            'kedudukan_keluarga'=>$kedudukan_keluarga,
            'pendidikan'=>$pendidikan,
            'kategori'=>$kategori,
        ]);
    }
    function actionPesertaStatusForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = UserDaftar::findOne($id);
            $model->scenario="update_status_as_admin";
            $status=['0'=>'Tidak Disetujui','Revisi','Disetujui'];
            return $this->renderAjax('peserta_status_form',[
                'model'=>$model,
                'status'=>$status,
            ]);
        }
    }
    function actionPesertaStatusSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = UserDaftar::findOne($id);
            $model->scenario="update_status_as_admin";
            $model->load($req->post());
            if($model->validate()){
                if($model->approvePesertaAsPasien()){
                    Yii::$app->session->setFlash('true','Status berhasil disimpan');
                    $result=['status'=>true];
                }else{
                    $result=['status'=>false,'msg'=>'Status gagal disimpan'];
                }
            }else{
                $result=['status'=>true,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionNewPesertaCheck()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $count=UserDaftar::find()->where("ud_approve_status='3'")->count();
            return $this->asJson($count);
        }
    }
    function actionGetKtp($id=NULL)
    {
        $model=UserDaftar::find()->select('ud_ktp')->where(['ud_id'=>$id])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage_daftar'].$model['ud_ktp'], $model['ud_ktp'], ['inline'=>true]);
        }
    }
}