<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\Setting;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
class SettingController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','pedoman-pemeriksaan-upload','pedoman-pemeriksaan-download','informasi-save','batas-daftar-save'],
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
                    'index'=>['get'],
                    'pedoman-pemeriksaan-upload'=>['post'],
                    'pedoman-pemeriksaan-download'=>['get'],
                    'informasi-save'=>['post'],
                    'batas-daftar-save'=>['post'],
                ],
            ],
        ];
    }
    function actionIndex()
    {
        $setting=Yii::$app->db->createCommand("
            select 
            (select set_value from ".Setting::tableName()." where set_kode='front_info_instansi') as info_instansi,
            (select set_value from ".Setting::tableName()." where set_kode='front_info_umum') as info_umum,
            (select set_value from ".Setting::tableName()." where set_kode='batas_jam_daftar') as batas_jam_daftar
        ")->queryOne();
        return $this->render('index',[
            'setting'=>$setting,
        ]);
    }
    function actionPedomanPemeriksaanUpload()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $file=UploadedFile::getInstanceByName('berkas');
            $type=$req->post('type');
            $model = Setting::findOne(['set_kode'=>$type=='umum' ? 'pedoman_pemeriksaan_umum' : 'pedoman_pemeriksaan_instansi']);
            $oldfile=$model['set_value'];
            $model->set_value=str_replace('.','',microtime(true)).'.'.strtolower($file->extension);
            if($model->validate()){
                if($model->save(false)){
                    if(!empty($oldfile)){
                        if(file_exists(Yii::$app->params['storage_app'].$oldfile) && is_file(Yii::$app->params['storage_app'].$oldfile)){
                            unlink(Yii::$app->params['storage_app'].$oldfile);
                        }
                    }
                    $file->saveAs(Yii::$app->params['storage_app'].$model->set_value);
                    $result=['status'=>true,'msg'=>'Berkas berhasil diupload'];
                }else{
                    $result=['status'=>false,'msg'=>'Berkas gagal diupload'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionPedomanPemeriksaanDownload($q)
    {
        $model = Setting::find()->where(['set_kode'=>$q=='u' ? 'pedoman_pemeriksaan_umum' : 'pedoman_pemeriksaan_instansi'])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage_app'].$model['set_value'], $model['set_value'], ['inline'=>true]);
        }
        Yii::$app->session->setFlash('error','Berkas tidak ditemukan');
        return $this->redirect(['index']);
    }
    function actionInformasiSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $isi=$req->post('deskripsi');
            $type=$req->post('type');
            $model = Setting::findOne(['set_kode'=>$type=='i' ? 'front_info_instansi' : 'front_info_umum' ]);
            $model->set_value=$isi;
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Data berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Data gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionBatasDaftarSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $model = Setting::findOne(['set_kode'=>'batas_jam_daftar']);
            $model->set_value=$req->post('jam');
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Data berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Data gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
}