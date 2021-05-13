<?php
namespace app\controllers;
use Yii;
use yii\helpers\Url;
use app\widgets\App;
use app\models\User;
use app\models\UserDaftar;
use app\models\UserSearch;
use app\models\UserDaftarSearch;
use app\models\Pekerjaan;
use app\models\Agama;
use app\models\Informasi;
use app\models\JenisMcu;
use app\models\Berkas;
use app\models\UserBerkas;
use app\models\UserKusionerBiodata;
use app\models\Kuisioner;
use app\models\Setting;
use app\models\KategoriKuisioner;
use app\models\UserKuisioner;
use app\models\Paket;
use app\models\PaketTindakan;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use Mpdf\Mpdf;
class PesertaUmumController extends Controller
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
                        'actions' => ['biodata','biodata-save','upload-ktp','get-ktp','informasi','check-status-approve','riwayat','paket-detail'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isPeserta() || App::isPasien()){
                                return true;    
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['daftar','jenis-mcu-keterangan','daftar-save','berkas','berkas-save','get-berkas','kuisioner-sosial','kuisioner-sosial-save','kuisioner-penyakit','kuisioner-penyakit-save','kuisioner-preemployee','kuisioner-preemployee-save','selesai','selesai-bukti-pendaftaran','selesai-save','cetak-riwayat'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(App::isPeserta()){
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
                    //peserta/pasien
                    'check-status-approve'=>['post'],
                    'biodata' => ['get'],
                    'biodata-save' => ['post'],
                    'upload-ktp' => ['post'],
                    'get-ktp'=>['get'],
                    'informasi'=>['get'],
                    'daftar' => ['get'],
                    'paket-detail' => ['post'],
                    'daftar-save' => ['post'],
                    'jenis-mcu-keterangan'=>['post'],
                    'berkas'=>['get'],
                    'berkas-save'=>['post'],
                    'get-berkas'=>['get'],
                    'kuisioner-sosial'=>['get'],
                    'kuisioner-sosial-save'=>['post'],
                    'kuisioner-penyakit'=>['get'],
                    'kuisioner-penyakit-save'=>['post'],
                    'kuisioner-preemployee'=>['get'],
                    'kuisioner-preemployee-save'=>['post'],
                    'selesai'=>['get'],
                    'selesai-save'=>['post'],
                    'selesai-bukti-pendaftaran'=>['get'],
                    'riwayat'=>['get'],
                    'cetak-riwayat'=>['get'],
                ],
            ],
        ];
    }
    function actionCheckStatusApprove()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $user=Yii::$app->user->identity;
            if($user->ud_approve_status=='2'){
                Yii::$app->session->setFlash('true','Akun anda sudah disetujui, silahkan daftar Medikal Check Up');
                $result=['status'=>true];
            }elseif($user->ud_approve_status=='0' || $user->ud_approve_status=='1'){
                $result=['status'=>false,'msg'=>$user->ud_approve_ket];
            }else{
                $result=['status'=>false];
            }
            return $this->asJson($result);
        }
    }
	function actionBiodata()
	{
        $user=Yii::$app->user->identity;
        if(isset($user->ud_id)){
            $model = UserDaftar::findOne($user->ud_id);
            $model->ud_tgl_lahir=date('d-m-Y',strtotime($model->ud_tgl_lahir));
        }else{
            $model = new UserDaftar();
            $model->setDataByPasien();
        }
        $model->scenario=isset($user->ud_id) ? "update_akun_as_peserta" : "update_akun_as_pasien";
        $is_disabled=!isset($user->ud_id) ? true : ($model->ud_approve_status=='2' ? true : false);
        $pekerjaan=Pekerjaan::all();
        $agama=Agama::all();
		return $this->render('biodata', [
            'user'=>$user,
            'model' => $model,
            'pekerjaan'=>$pekerjaan,
            'pendidikan'=>$this->pendidikan,
            'agama'=>$agama,
            'is_disabled'=>$is_disabled
        ]);
    }
    function actionBiodataSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $user=Yii::$app->user->identity;
            if(isset($user->ud_id)){
                $model = UserDaftar::findOne($user->ud_id);
            }else{
                $model = new UserDaftar();
            }
            $model->scenario=isset($user->ud_id) ? "update_akun_as_peserta" : "update_akun_as_pasien";
            $model->load($req->post());
            if($model->validate()){
                if($model->save(false)){
                    if(!isset($user->ud_id)){
                        Yii::$app->user->logout();
                    }
                    $result=['status'=>true,'msg'=>!isset($user->ud_id) ? 'Biodata berhasil diupdate. Anda akan logout dari aplikasi secara otomatis untuk keperluan autentikasi. Silahkan login kembali untuk melanjutkan pendaftaran anda' : 'Biodata berhasil disimpan'.( $model->ud_ktp==NULL ? '. <br><b>Silahkan UPLOAD KTP anda</b>' : NULL ),'o'=>!isset($user->ud_id) ? true : false]; //o = old, pasien lama
                }else{
                    $result=['status'=>false,'msg'=>'Biodata gagal diupdate'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionUploadKtp()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $user=Yii::$app->user->identity;
            if(!isset($user->ud_id)){
                $result=['status'=>false,'msg'=>'Silahkan lengkapi biodata terlebih dahulu'];
            }else{
                $ktp=UploadedFile::getInstanceByName('berkas');
                $model = UserDaftar::findOne($user->ud_id);
                $model->scenario="upload_ktp";
                $oldfile=$model->ud_ktp;
                $model->ud_ktp=$model->ud_id.'-'.str_replace('.','',microtime(true)).'.'.strtolower($ktp->extension);
                if($model->validate()){
                    if($model->save(false)){
                        if(!empty($oldfile)){
                            if(file_exists(Yii::$app->params['storage_daftar'].$oldfile) && is_file(Yii::$app->params['storage_daftar'].$oldfile)){
                                unlink(Yii::$app->params['storage_daftar'].$oldfile);
                            }
                        }
                        $file_path=Yii::$app->params['storage_daftar'].$model->ud_ktp;
                        $ktp->saveAs($file_path);
                        $result=['status'=>true,'msg'=>'KTP berhasil diupload','file'=>Url::to(['get-ktp'])];
                    }else{
                        $result=['status'=>false,'msg'=>'Upload gagal dilakukan'];
                    }
                }else{
                    $result=['status'=>false,'msg'=>$model->errors];
                }
            }
            return $this->asJson($result);
        }
    }
    function actionGetKtp($id=NULL)
    {
        if(App::isPeserta()){
            $id=Yii::$app->user->identity->ud_id;
        }
        $model=UserDaftar::find()->select('ud_ktp')->where(['ud_id'=>$id])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage_daftar'].$model['ud_ktp'], $model['ud_ktp'], ['inline'=>true]);
        }
    }
    function actionInformasi()
    {
        $informasi=Informasi::find()->where(['i_jenis'=>1])->orderBy(['i_urut'=>SORT_ASC])->asArray()->all();
        return $this->render('informasi',['informasi'=>$informasi]);
    }
    function actionDaftar()
    {
        $nik=Yii::$app->user->identity->ud_nik;
        $user=UserDaftar::find()->where(['ud_nik'=>$nik])->asArray()->limit(1)->one();
        if($user!=NULL){
            if($user['ud_update_biodata_at']==NULL){
                Yii::$app->session->setFlash('false','Silahkan lengkapi biodata anda');
                return $this->redirect(['biodata']);
            }elseif($user['ud_ktp']==NULL){
                Yii::$app->session->setFlash('false','Silahkan upload KTP');
                return $this->redirect(['biodata']);
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lengkapi biodata anda');
            return $this->redirect(['biodata']);
        }
        $check=User::getLatest($nik);
        if($check!=NULL){
            $model=$check;
            $model->is_riwayat_mcu='n';
            if(!empty($model->u_tgl_terakhir_mcu)){
                $model->is_riwayat_mcu='y';
            }
        }else{
            $model = new User();
            $model->is_riwayat_mcu='n';
        }
        $model->scenario="peserta_daftar";
        $model->u_nik=$nik;
        $jenis_mcu=JenisMcu::find()->where(['jm_status'=>'1'])->asArray()->all();
        
        $setting=Yii::$app->db->createCommand("
            select 
                (select set_value from ".Setting::tableName()." where set_kode='batas_jam_daftar') as batas_jam_daftar
        ")->queryOne();
        $disable_now=strtotime(date('H:i'))>strtotime($setting['batas_jam_daftar']) ? [date('d-m-Y')] : [];
        $paket=Paket::find()->where(['is_active'=>1,'jenis_paket'=>1])->asArray()->all();
        return $this->render('daftar_index',[
            'model'=>$model,
            'jenis_mcu'=>$jenis_mcu,
            'disable_now'=>$disable_now,
            'setting'=>$setting,
            'paket'=>$paket,
        ]);
    }
    function actionPaketDetail()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $detail=PaketTindakan::find()->select("nama_tindakan,harga")->where(['kode_paket'=>$id])->asArray()->all();
            return $this->renderPartial('daftar_paket_detail',[
                'detail'=>$detail,
            ]);
        }
    }
    function actionJenisMcuKeterangan()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $jenis=JenisMcu::find()->where(['jm_id'=>$id])->asArray()->limit(1)->one();
            return $this->renderPartial('jenis_mcu_ket',[
                'jenis'=>$jenis,
            ]);
        }
    }
    function actionDaftarSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $update=$req->post('update');
            if(!empty($update)){
                $model = User::findOne($update);
            }else{
                $model = new User();
            }
            $model->scenario="peserta_daftar";
            $model->load($req->post());
            $model->setDataUser();
            if($model->validate()){
                if($model->save(false)){
                    $result=['status'=>true,'msg'=>'Pendaftaran berhasil disimpan','update'=>$model->u_id];
                }else{
                    $result=['status'=>false,'msg'=>'Pendaftaran gagal disimpan'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionBerkas()
    {
        $nik=Yii::$app->user->identity->ud_nik;
        $user=User::getLatest($nik);
        if($user!=NULL){
            if($user['u_biodata_finish_at']==NULL){
                Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
                return $this->redirect(['daftar']);
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
            return $this->redirect(['daftar']);
        }
        $berkas=Berkas::find()->with([
            'userberkas'=>function($q) use($user){
                $q->andWhere(['ub_user_id'=>$user['u_id']]);
            }
        ])->select('b_id,b_nama')->where(['b_status'=>'1'])->asArray()->all();
        $is_reg_close=$user['u_finish_at']!=NULL ? true : false;
        return $this->render('daftar_berkas',[
            'berkas'=>$berkas,
            'is_reg_close'=>$is_reg_close,
            'user'=>$user,
        ]);
    }
    function actionBerkasSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $nik=Yii::$app->user->identity->ud_nik;
            $user=User::getLatest($nik);
            $id=$req->post('id');
            $file=UploadedFile::getInstanceByName('berkas');
            $model = UserBerkas::find()->where(['ub_user_id'=>$user['u_id'],'ub_berkas_id'=>$id])->limit(1)->one();
            if($model==NULL){
                $model = new UserBerkas();
                $model->ub_user_id=$user['u_id'];
                $model->ub_berkas_id=$id;
            }else{
                $model->tmp_old_file=$model->ub_berkas;
            }
            $model->tmp_file=$file;
            if($model->saveBerkas($user)){
                $result=['status'=>true,'msg'=>'Berkas berhasil diupload','id'=>$model->ub_berkas_id];
            }else{
                $result=['status'=>false,'msg'=>$model->msg];
            }
            return $this->asJson($result);
        }
    }
    function actionGetBerkas($data)
    {
        $user=Yii::$app->user->identity;
        $daftar=User::getLatest($user->ud_nik);
        $model = UserBerkas::find()->where(['ub_user_id'=>$daftar['u_id'],'ub_berkas_id'=>$data])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage'].$model['ub_berkas'], $model['ub_berkas'], ['inline'=>true]);
        }
        Yii::$app->session->setFlash('error','Berkas tidak ditemukan');
        return $this->redirect(['index']);
    }
    function actionKuisionerSosial()
    {
        $user=Yii::$app->user->identity;
        $daftar=User::getLatest($user->ud_nik);
        if($daftar!=NULL){
            if($daftar['u_berkas_finish_at']==NULL){
                Yii::$app->session->setFlash('false','Silahkan upload berkas anda terlebih dahulu');
                return $this->redirect(['berkas']);
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
            return $this->redirect(['daftar']);
        }
        $model_biodata_tmp=UserKusionerBiodata::find()->where(['ukb_user_id'=>$daftar['u_id']])->orderBy(['ukb_created_at'=>SORT_DESC])->limit(1)->one();
        $model_biodata = $model_biodata_tmp!=NULL ? $model_biodata_tmp : new UserKusionerBiodata();
        $model_biodata->ukb_user_id=$daftar['u_id'];
        if($model_biodata->isNewRecord){
            $model_biodata->is_sebelum='n';
            if($user->ud_pekerjaan=='011'){
                $model_biodata->is_sekarang='n';
            }else{
                $model_biodata->is_sekarang='y';
                $model_biodata->ukb_krj_skrg=$daftar['u_pekerjaan_nama'];
                $model_biodata->ukb_krj_skrg_perusahaan=$daftar['u_alamat_pekerjaan'];
            }
            $model_biodata->is_dituju='n';
            if($daftar->u_jenis_mcu_id==1){ //jika pre employee, cpns=>pns, magang=>tetap, maka pekerjaan dituju y
                $model_biodata->is_dituju='y';
            }
        }else{
            $model_biodata->is_sebelum='n';
            $model_biodata->is_sekarang='n';
            $model_biodata->is_dituju='n';
            if($model_biodata->ukb_krj_sebelum!=NULL){
                $model_biodata->is_sebelum='y';
            }
            if($model_biodata->ukb_krj_skrg!=NULL){
                $model_biodata->is_sekarang='y';
            }
            if($model_biodata->ukb_krj_dituju!=NULL){
                $model_biodata->is_dituju='y';
            }
        }
        $kuisioner_sosial = Kuisioner::find()->where(['kk_id'=>4])->andWhere('k_id_parent IS NULL')->asArray()->all();
        $is_reg_close=$daftar['u_finish_at']!=NULL ? true : false;
        return $this->render('daftar_kuisioner_sosial',[
            'daftar'=>$daftar,
            'model'=>$model_biodata,
            'kuisioner_sosial'=>$kuisioner_sosial,
            'is_reg_close'=>$is_reg_close,
        ]);
    }
    function actionKuisionerSosialSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $update=$req->post('update');
            $model = $update!=NULL ? UserKusionerBiodata::findOne($update) : new UserKusionerBiodata();
            $model->load($req->post());
            if($model->validate()){
                if($model->saveData($req->post())){
                    $result=['status'=>true,'msg'=>'Data berhasil disimpan','id'=>$model->ukb_id];
                }else{
                    $result=['status'=>false,'msg'=>'Data gagal disimpan, silahkan periksa kembali isian'];
                }
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionKuisionerPenyakit()
    {
        $user=Yii::$app->user->identity;
        $daftar=User::getLatest($user->ud_nik);
        if($daftar!=NULL){
            if($daftar['u_kuisioner1_finish_at']==NULL){
                Yii::$app->session->setFlash('false','Silahkan isi kuisioner sosial terlebih dahulu');
                return $this->redirect(['kuisioner-sosial']);
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
            return $this->redirect(['daftar']);
        }
        $query_kategori_kuisioner=KategoriKuisioner::find()->where('kk_id != 4 and kk_id != 5');
        if($user->ud_jkel=='L'){
            $query_kategori_kuisioner->andWhere('kk_id != 3');
        }
        $kategori_kuisioner=$query_kategori_kuisioner->asArray()->all();
        $is_reg_close=$daftar['u_finish_at']!=NULL ? true : false;
        return $this->render('daftar_kuisioner_penyakit',[
            'daftar'=>$daftar,
            'kategori_kuisioner'=>$kategori_kuisioner,
            'is_reg_close'=>$is_reg_close,
        ]);
    }
    function actionKuisionerPenyakitSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $data=$req->post();
            $m = new UserKusionerBiodata();
            if($m->saveRiwayatPenyakit($data)){
                $result=['status'=>true,'msg'=>'Data berhasil disimpan'];
            }else{
                $result=['status'=>false,'msg'=>'Data gagal disimpan, silahkan periksa kembali isian'];
            }
            return $this->asJson($result);
        }
    }
    function actionKuisionerPreemployee()
    {
        $user=Yii::$app->user->identity;
        $daftar=User::getLatest($user->ud_nik);
        if($daftar!=NULL){
            if($daftar['u_kuisioner2_finish_at']==NULL){
                Yii::$app->session->setFlash('false','Silahkan isi kuisioner riwayat penyakit terlebih dahulu');
                return $this->redirect(['kuisioner-penyakit']);
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
            return $this->redirect(['daftar']);
        }
        $kategori_kuisioner_cpns=KategoriKuisioner::find()->where('kk_id = 5')->asArray()->limit(1)->all();
        $is_reg_close=$daftar['u_finish_at']!=NULL ? true : false;
        return $this->render('daftar_kuisioner_preemployee',[
            'daftar'=>$daftar,
            'kategori_kuisioner_cpns'=>$kategori_kuisioner_cpns,
            'is_reg_close'=>$is_reg_close,
        ]);
    }
    function actionKuisionerPreemployeeSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $data=$req->post();
            $m = new UserKusionerBiodata();
            if($m->saveRiwayatCpns($data)){
                $result=['status'=>true,'msg'=>'Data berhasil disimpan'];
            }else{
                $result=['status'=>false,'msg'=>'Data gagal disimpan, silahkan periksa kembali isian'];
            }
            return $this->asJson($result);
        }
    }
    function actionSelesai()
    {
        $user=Yii::$app->user->identity;
        $daftar=User::getLatest($user->ud_nik);
        if($daftar!=NULL){
            if($daftar->u_jenis_mcu_id==1){
                if($daftar['u_kuisioner3_finish_at']==NULL){
                    Yii::$app->session->setFlash('false','Silahkan isi kuisioner pre employee terlebih dahulu');
                    return $this->redirect(['kuisioner-penyakit']);
                }
            }else{
                if($daftar['u_kuisioner2_finish_at']==NULL){
                    Yii::$app->session->setFlash('false','Silahkan isi kuisioner riwayat penyakit terlebih dahulu');
                    return $this->redirect(['kuisioner-penyakit']);
                }
            }
        }else{
            Yii::$app->session->setFlash('false','Silahkan lakukan pendaftaran MCU terlebih dahulu');
            return $this->redirect(['daftar']);
        }
        return $this->render('daftar_selesai',[
            'daftar'=>$daftar,
        ]);
    }
    function actionSelesaiSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $user=Yii::$app->user->identity;
            $model = User::find()->where(['u_nik'=>$user->ud_nik])->andWhere("u_tgl_periksa >= '".date('Y-m-d')."' and u_finish_mcu_at is null")->orderBy(['u_created_at'=>SORT_DESC])->limit(1)->one();
            if($model->finishRegUmum()){
                Yii::$app->session->setFlash('success','Pendaftaran sudah selesai, silahkan download file bukti pendaftaran');
            }else{
                Yii::$app->session->setFlash('error','Maaf, terjadi kesalahan, silahkan hubungi administrator');
            }
            return $this->asJson(['status'=>true]);
        }
    }
    function actionSelesaiBuktiPendaftaran()
    {
        $tmp=Yii::$app->user->identity;
        $user=User::getLatest($tmp->ud_nik);
        $kuisioner_sosial = Kuisioner::find()->where(['kk_id'=>4])->andWhere('k_id_parent IS NULL')->asArray()->all();
		$query_kategori_kuisioner=KategoriKuisioner::find()->where('kk_id != 4 and kk_id != 5');
        if($user['u_jkel']=='L'){
            $query_kategori_kuisioner->andWhere('kk_id != 3');
        }
        $kategori_kuisioner=$query_kategori_kuisioner->asArray()->all();
        $bukti = $this->renderPartial('pdf_bukti',['user'=>$user]);
        $persetujuan = $this->renderPartial('pdf_setuju',['u'=>$user]);
        // $pelepasan = $this->renderPartial('pdf_lepas',['u'=>$user]);
        $kuisioner = $this->renderPartial('pdf_kuisioner',['u'=>$user,'kuisioner_sosial'=>$kuisioner_sosial,'kategori_kuisioner'=>$kategori_kuisioner]);
		$pdf = new Mpdf([
            'default_font'=>'Arial',
            'default_font_size' => 11
        ]);
        $pdf->tMargin=10;
        $pdf->simpleTables=true;
        $pdf->packTableData=true;
        $pdf->useSubstitutions = false;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit = 1;

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>[210,150],
			'margin-top'=>10,
        ]);
        $pdf->SetHTMLFooter('<small style="font-size:10px;"><i>dicetak pada : '.date('d-m-Y H:i').'</i></small>','O');
        $pdf->WriteHTML($bukti);

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>'A4',
			'margin-top'=>20
        ]);
        $pdf->WriteHTML($persetujuan);

        // $pdf->AddPageByArray([
        //     'orientation'=>'P',
        //     'sheet-size'=>'A4',
        // ]);
        // $pdf->WriteHTML($pelepasan);

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>'A4',
        ]);
        $pdf->WriteHTML($kuisioner);
        
        if($user['u_jenis_mcu_id']==1){
            $kuisioner_cpns=UserKuisioner::find()->where(['u_id'=>$user['u_id']])->joinWith([
                'kuisioner'=>function($q){
                    $q->andWhere(['kk_id'=>5]);
                }
            ])->asArray()->all();
            $cpns = $this->renderPartial('pdf_cpns',['u'=>$user,'kuisioner_cpns'=>$kuisioner_cpns]);
            $pdf->AddPageByArray([
                'orientation'=>'P',
                'sheet-size'=>'A4',
            ]);
            $pdf->WriteHTML($cpns);
        }
        $pdf->Output('LAPORAN HASIL PENGUJIAN_'.date('d-m-Y H:i:s').'.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
    function actionRiwayat()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchAsRiwayat(Yii::$app->request->queryParams);
        $jenis_mcu=JenisMcu::find()->asArray()->all();
        return $this->render('riwayat',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis_mcu'=>$jenis_mcu,
        ]);
    }
    function actionCetakRiwayat($id)
    {
        $user=User::find()->with(['jadwal','kuisionerbiodata',
            'jadwalperiksa'=>function($q){
                $q->with(['permintaan'=>function($q){
                    $q->with(['user']);
                }]);
            }
        ])->where(['u_id'=>$id])->asArray()->limit(1)->one();
        $kuisioner_sosial = Kuisioner::find()->where(['kk_id'=>4])->andWhere('k_id_parent IS NULL')->asArray()->all();
		$query_kategori_kuisioner=KategoriKuisioner::find()->where('kk_id != 4 and kk_id != 5');
        if($user['u_jkel']=='L'){
            $query_kategori_kuisioner->andWhere('kk_id != 3');
        }
        $kategori_kuisioner=$query_kategori_kuisioner->asArray()->all();
        $bukti = $this->renderPartial('pdf_bukti',['user'=>$user]);
        $persetujuan = $this->renderPartial('pdf_setuju',['u'=>$user]);
        // $pelepasan = $this->renderPartial('pdf_lepas',['u'=>$user]);
        $kuisioner = $this->renderPartial('pdf_kuisioner',['u'=>$user,'kuisioner_sosial'=>$kuisioner_sosial,'kategori_kuisioner'=>$kategori_kuisioner]);
		$pdf = new Mpdf([
            'default_font'=>'Arial',
            'default_font_size' => 11
        ]);
        $pdf->tMargin=10;
        $pdf->simpleTables=true;
        $pdf->packTableData=true;
        $pdf->useSubstitutions = false;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit = 1;

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>[210,150],
			'margin-top'=>10,
        ]);
        $pdf->SetHTMLFooter('<small style="font-size:10px;"><i>dicetak pada : '.date('d-m-Y H:i').'</i></small>','O');
        $pdf->WriteHTML($bukti);

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>'A4',
			'margin-top'=>20
        ]);
        $pdf->WriteHTML($persetujuan);

        // $pdf->AddPageByArray([
        //     'orientation'=>'P',
        //     'sheet-size'=>'A4',
        // ]);
        // $pdf->WriteHTML($pelepasan);

        $pdf->AddPageByArray([
            'orientation'=>'P',
            'sheet-size'=>'A4',
        ]);
        $pdf->WriteHTML($kuisioner);
        
        if($user['u_jenis_mcu_id']==1){
            $kuisioner_cpns=UserKuisioner::find()->where(['u_id'=>$user['u_id']])->joinWith([
                'kuisioner'=>function($q){
                    $q->andWhere(['kk_id'=>5]);
                }
            ])->asArray()->all();
            $cpns = $this->renderPartial('pdf_cpns',['u'=>$user,'kuisioner_cpns'=>$kuisioner_cpns]);
            $pdf->AddPageByArray([
                'orientation'=>'P',
                'sheet-size'=>'A4',
            ]);
            $pdf->WriteHTML($cpns);
        }
        $pdf->Output('LAPORAN HASIL PENGUJIAN_'.date('d-m-Y H:i:s').'.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
}