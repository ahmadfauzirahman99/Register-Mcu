<?php
namespace app\controllers;
use Yii;
use app\widgets\App;
use app\models\User;
use app\models\Pekerjaan;
use app\models\UserSearch;
use app\models\UserPermintaan;
use app\models\UserPermintaanPaket;
use app\models\UserPermintaanJadwal;
use app\models\UserPermintaanSearch;
use app\models\Debitur;
use app\models\KategoriKuisioner;
use app\models\JenisMcu;
use app\models\Paket;
use app\models\PaketTindakan;
use app\models\Agama;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;
class InstansiPermintaanController extends Controller
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
                        'actions' => ['index','form','save','view','peserta-form','peserta-save','delete','paket-detail','peserta-delete','import'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isInstansi()){
                                return true;    
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index','view','status-form','status-save','jadwal-form','jadwal-save','jadwal-delete','pdf','peserta-form'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter() || App::isRm()){
                                return true;    
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['peserta-view'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isDokter()){
                                return true;    
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['edit','peserta-detail','peserta-verify-form','peserta-verify-save'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isRm()){
                                return true;    
                            }
                            return false;
                        }
                    ],
                    [
                        'actions'=>['get-ktp'],
                        'allow'=>true,
                        'roles' => ['@'],
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
                    'index' => ['get'],
                    'form'=>['post'],
                    'save'=>['post'],
                    'view'=>['get'],
                    'pdf'=>['get'],
                    'delete'=>['post'],
                    'status-form'=>['post'],
                    'status-save'=>['post'],
                    'jadwal-form'=>['post'],
                    'jadwal-save'=>['post'],
                    'jadwal-delete'=>['post'],
                    'peserta-form'=>['post'],
                    'get-ktp'=>['get'],
                    'paket-detail'=>['post'],
                    'peserta-save'=>['post'],
                    'peserta-delete'=>['post'],
                    'import'=>['post'],
                    'peserta-view'=>['get'], //lihat detail data isian peserta oleh dokter

                    'edit'=>['get','post'],
                    'peserta-detail'=>['get'],
                    'peserta-verify-form'=>['post'],
                    'peserta-verify-save'=>['post']
                ],
            ],
        ];
    }
    function actionIndex()
    {
        if(App::isInstansi()){
            $user=Yii::$app->user->identity;
            if($user->u_no_hp==NULL && $user->u_nama_petugas==NULL){
                Yii::$app->session->setFlash('false','Silahkan lengkapi data perusahaan terlebih dahulu');
                return $this->redirect(['/instansi-profile']);
            }
        }
        $searchModel = new UserPermintaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $jenis_mcu=JenisMcu::find()->where(['jm_status'=>'1'])->asArray()->all();
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis_mcu'=>$jenis_mcu,
        ]);
    }
    function actionForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = $id!=NULL ? UserPermintaan::findOne($id) : new UserPermintaan();
            $model->scenario="create";
            $disable_tgl=array_map(function($q){
                return date('d-m-Y',strtotime($q));
            },array_column(UserPermintaanJadwal::getAllThisyear(),'upj_tgl'));
            $jenis_mcu=JenisMcu::find()->where(['jm_status'=>'1'])->asArray()->all();
            return $this->renderAjax('form',[
                'model'=>$model,
                'disable_tgl'=>$disable_tgl,
                'jenis_mcu'=>$jenis_mcu,
            ]);
        }
    }
    function actionSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = $id!=NULL ? UserPermintaan::findOne($id) : new UserPermintaan();
            $model->scenario="create";
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Permintaan berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Permintaan gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionView($id)
    {
        $model = UserPermintaan::find()->with([
            'user',
            'paketpemeriksaan'=>function($q){
                $q->with(['paket']);
            }
        ,'jadwal','debitur','jenismcu'])->where(['up_id'=>$id])->asArray()->limit(1)->one();
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchByPermintaan($id,Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=50;
        $paket=Paket::find()->where(['jenis_paket'=>2,'is_active'=>1])->asArray()->all();
        return $this->render('view',[
            'model'=>$model,
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'paket'=>$paket,
        ]);
    }
    function actionDelete()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = UserPermintaan::findOne($id);
            if($model->delete()){
                $result=['status'=>true,'msg'=>'Permintaan berhasil dihapus'];
            }else{
                $result=['status'=>false,'msg'=>'Permintaan gagal dihapus'];
            }
            return $this->asJson($result);
        }
    }
    function actionStatusForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = UserPermintaan::findOne($id);
            $model->scenario="update_status_by_dokter";
            $debitur=Debitur::allAktif();
            $paket=Paket::find()->where(['is_active'=>'1'])->andWhere(['or',['jenis_paket'=>'2'],['jenis_paket'=>'3']])->asArray()->all();
            if(count($model->paketpemeriksaan)>0){
                $model->up_paket_id=array_column($model->paketpemeriksaan,'upp_paket_id');
            }
            return $this->renderAjax('status_form',[
                'model'=>$model,
                'debitur'=>$debitur,
                'paket'=>$paket,
            ]);
        }
    }
    function actionStatusSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = UserPermintaan::findOne($id);
            $model->scenario="update_status_by_dokter";
            $model->load($req->post());
            if($model->validate()){
                if($model->saveStatus()){
                    $result=['status'=>true,'msg'=>'Status berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Status gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionJadwalForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $up_id=$req->post('up_id');
            $id=$req->post('id');
            $model = $id!=NULL ? UserPermintaanJadwal::findOne($id) : new UserPermintaanJadwal();
            if($model->isNewRecord){
                $model->upj_up_id=$up_id;
                $model->scenario="create_by_dokter";
            }else{
                $model->scenario="update_by_dokter";
            }
            $model->auto_set_jadwal=1;
            return $this->renderAjax('jadwal_form',[
                'model'=>$model,
                'up_id'=>$up_id,
            ]);
        }
    }
    function actionJadwalSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = $id!=NULL ? UserPermintaanJadwal::findOne($id) : new UserPermintaanJadwal();
            if($model->isNewRecord){
                $model->scenario="create_by_dokter";
            }else{
                $model->scenario="update_by_dokter";
            }
            $model->load($req->post());
            if($model->validate()){
                if($model->saveJadwal()){
                    $result=['status'=>true,'msg'=>'Jadwal berhasil disimpan'];
                }else{
                    $result=['status'=>false,'msg'=>'Jadwal gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionJadwalDelete()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = UserPermintaanJadwal::findOne($id);
            if($model->delete()){
                Yii::$app->session->setFlash('true','Jadwal berhasil dihapus');
                $result=['status'=>true];
            }else{
                $result=['status'=>false,'msg'=>'Jadwal gagal dihapus'];
            }
            return $this->asJson($result);
        }
    }
    function actionPesertaView($permintaan,$id)
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
        return $this->render('peserta_view',[
            'permintaan'=>$permintaan,
            'model'=>$model,
            'status_marital'=>$status_marital,
            'kedudukan_keluarga'=>$kedudukan_keluarga,
            'pendidikan'=>$pendidikan,
            'kategori'=>$kategori,
        ]);
    }
    function actionPesertaForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $up=$req->post('up');
            $permintaan=UserPermintaan::findOne($up);
            $model = $id!=NULL ? User::findOne($id) : new User();
            $model->scenario="create_peserta_by_instansi";
            $model->u_tgl_lahir=$model->u_tgl_lahir!=NULL ? date('d-m-Y',strtotime($model->u_tgl_lahir)) : NULL;
            $pekerjaan=Pekerjaan::all();
            $jadwalperiksa=UserPermintaanJadwal::all($model->isNewRecord ? $up : $model->jadwalperiksa->upj_up_id);
            $tmp_paket=UserPermintaanPaket::find()->select('upp_paket_id')->with(['paket'])->where(['upp_up_id'=>$up])->asArray()->all();
            $paket=[];
            if(count($tmp_paket)>0){
                foreach($tmp_paket as $p){
                    if($p['paket']!=NULL){
                        $paket[]=['kode'=>$p['paket']['kode'],'nama'=>$p['paket']['nama']];
                    }
                }
            }
            return $this->renderAjax('peserta_form',[
                'model'=>$model,
                'pekerjaan'=>$pekerjaan,
                'jadwalperiksa'=>$jadwalperiksa,
                'permintaan'=>$permintaan,
                'paket'=>$paket,
            ]);
        }
    }
    function actionPaketDetail()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $detail=PaketTindakan::find()->select("nama_tindakan,harga")->where(['kode_paket'=>$id])->asArray()->all();
            return $this->renderPartial('paket_detail',[
                'detail'=>$detail,
            ]);
        }
    }
    function actionPesertaSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $update=$req->post('update');
            $old_ktp=NULL;
            if($update!=NULL){
                $model=User::findOne($update);
                $old_ktp=$model->u_ktp;
            }else{
                $model = new User();
            }
            $model->scenario="create_peserta_by_instansi";
            $model->load($req->post());
            $model->ktp=UploadedFile::getInstanceByName('User[ktp]');
            if($model->validate()){
                $jadwal=UserPermintaanJadwal::find()->with(['permintaan'])->where(['upj_id'=>$model->u_upj_id])->asArray()->limit(1)->one();
                $query_count=User::find()->where(['u_upj_id'=>$model->u_upj_id]);
                if(!$model->isNewRecord){
                    $query_count->andWhere('u_id != :id',[':id'=>$model->u_id]);
                }
                $count=$query_count->count();
                if($count<$jadwal['upj_kuota']){
                    if($model->ktp!=NULL){
                        $model->u_ktp=$model->u_id.'-'.str_replace('.','',microtime(true)).'.'.strtolower($model->ktp->extension);
                    }
                    $user_permintaan=UserPermintaan::find()->where(['up_id'=>$jadwal['upj_up_id']])->asArray()->limit(1)->one();
                    $model->u_jenis_mcu_id=$user_permintaan!=NULL ? $user_permintaan['up_jenis_mcu_id'] : NULL;
                    $model->u_debitur_id=$jadwal['permintaan']['up_debitur_id'];
                    if($model->save(false)){
                        if($model->ktp!=NULL){
                            if($old_ktp!=NULL){
                                if(file_exists(Yii::$app->params['storage'].$old_ktp) && is_file(Yii::$app->params['storage'].$old_ktp)){
                                    unlink(Yii::$app->params['storage'].$old_ktp);
                                }
                            }
                            $model->ktp->saveAs(Yii::$app->params['storage'].$model->u_ktp);
                        }
                        $result=['status'=>true,'msg'=>'Peserta berhasil disimpan'];
                    }else{
                        $result=['status'=>false,'msg'=>'Peserta gagal disimpan'];
                    }
                }else{
                    $result=['status'=>false,'msg'=>'Maaf, kuota sudah penuh untuk tanggal '.date('d-m-Y',strtotime($jadwal['upj_tgl']))];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionPesertaDelete()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = User::findOne($id);
            $ktp=$model->u_ktp;
            if($model->u_biodata_finish_at==NULL){
                if($model->delete()){
                    if($ktp!=NULL){
                        if(file_exists(Yii::$app->params['storage'].$ktp) && is_file(Yii::$app->params['storage'].$ktp)){
                            unlink(Yii::$app->params['storage'].$ktp);
                        }
                    }
                    $result=['status'=>true,'msg'=>'Peserta berhasil dihapus']; 
                }else{
                   $result=['status'=>false,'msg'=>'Peserta gagal dihapus']; 
                }
            }else{
                $result=['status'=>false,'msg'=>'Peserta tidak bisa dihapus, karena telah/sedang melakukan pendaftaran online']; 
            }
            return $this->asJson($result);
        }
    }
    function actionEdit($id,$up)
    {
        $model = User::findOne($id);
        $req=Yii::$app->request;
        if($req->isPost){
            $model->load($req->post());
            if($model->save()){
                Yii::$app->session->setFlash('true','Biodata berhasil diupdate');
            }
        }
        $pekerjaan=Pekerjaan::all();
        $agama=Agama::all();
        return $this->render('edit',[
            'up'=>$up,
            'model'=>$model,
            'pekerjaan'=>$pekerjaan,
            'agama'=>$agama,
            'pendidikan'=>$this->pendidikan,
        ]);
    }
    function actionGetKtp($id)
    {
        $model=User::find()->select('u_ktp')->where(['u_id'=>$id])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage'].$model['u_ktp'], $model['u_ktp'], ['inline'=>true]);
        }
    }
    function actionImport()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $up=$req->post('up');
            $file=UploadedFile::getInstanceByName('import');
            $spreadsheet = IOFactory::load($file->tempName);
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $model = new User();
            if($model->importData($up,$data)){
                $result=['status'=>true,'msg'=>'Data berhasil diupload'];
            }else{
                $result=['status'=>false,'msg'=>$model->error_msg];
            }
            return $this->asJson($result);
        }
    }
    function actionPdf($id)
    {
        $data=UserPermintaanJadwal::find()->select('upj_id,upj_up_id,upj_tgl')
		->with(['permintaan'=>function($q){
            $q->select('up_nama');
        },'user'=>function($q){
            $q->select('u_nik,u_nama_depan,u_upj_id')->with(['paket'])->orderBy(['u_nama_depan'=>SORT_ASC]);
        }])->where(['upj_id'=>$id])->limit(1)->asArray()->one();
		$content=$this->renderPartial('pdf_peserta_per_jadwal',['data'=>$data]);
		$pdf = new Mpdf([
            'default_font'=>'Arial',
            'default_font_size' => 10
        ]);
        $pdf->tMargin=10;
        $pdf->simpleTables=true;
        $pdf->packTableData=true;
        $pdf->useSubstitutions = false;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit = 1;

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>'A4',
			'margin-left'=>10,
			'margin-right'=>10,
        ]);
        $pdf->WriteHTML($content);
        $pdf->Output('LAPORAN HASIL PENGUJIAN_'.date('d-m-Y H:i:s').'.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
    function actionPesertaDetail($id,$up)
    {
        $model = User::find()->with(['agama','pekerjaan'])->where(['u_id'=>$id])->asArray()->limit(1)->one();
        return $this->render('peserta_detail',[
            'up'=>$up,
            'model'=>$model,
            'status_marital'=>$this->status_marital,
            'pendidikan'=>$this->pendidikan,
        ]);
    }
    function actionPesertaVerifyForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model=User::findOne($id);
            $model->scenario="verify_by_rm";
            $status=['0'=>'Tidak Disetujui','Revisi','Disetujui'];
            return $this->renderAjax('peserta_verifikasi_form',[
                'model'=>$model,
                'status'=>$status,
            ]);
        }
    }
    function actionPesertaVerifySave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('update');
            $model = User::findOne($id);
            $model->scenario="verify_by_rm";
            $model->load($req->post());
            if($model->validate()){
                if($model->saveVerifikasiRm()){
                    Yii::$app->session->setFlash('true','Verifikasi berhasil dilakukan');
                    $result=['status'=>true];
                }else{
                    $result=['status'=>false,'msg'=>'Verifikasi gagal disimpan, silahkan periksa kembali'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
}