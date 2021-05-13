<?php

namespace app\controllers;

use app\components\Model;
use Yii;
use app\widgets\App;
use app\models\User;
use app\models\Berkas;
use app\models\UserBerkas;
use app\models\LoginForm;
use app\models\Informasi;
use app\models\Pekerjaan;
use app\models\Agama;
use app\models\Jadwal;
use app\models\Kuisioner;
use app\models\KategoriKuisioner;
use app\models\MasterAnak;
use app\models\NamaPasangan;
use app\models\UserKusionerBiodata;
use app\models\PelayananMcu;
use app\models\UserBrother;
use app\models\UserDetail;
use app\models\UserKuisioner;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\NotFoundHttpException;
use Mpdf\Mpdf;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['peserta-pdf', 'photo', 'index', 'captcha', 'captcha_daftar', 'offline', 'list-peserta'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['biodata', 'berkas', 'kuisioner-sosial', 'kuisioner-penyakit', 'kuisioner-anamnesa', 'selesai', 'file', 'informasi'],
                        'allow' => true,
                        'matchCallback' => function () {
                            if (App::isPesertaInstansi()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['bukti-pendaftaran'],
                        'allow' => true,
                        'matchCallback' => function () {
                            if (App::isPesertaInstansi() && App::isFinish()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['biodata-save', 'berkas-upload', 'kuisioner-sosial-save', 'kuisioner-penyakit-save', 'kuisioner-anamnesa-save', 'selesai-save'],
                        'allow' => true,
                        'matchCallback' => function () {
                            if (App::isPesertaInstansi() && !App::isRegClose()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    $url = Yii::$app->urlManager->createUrl('/auth/index');
                    return $this->redirect($url);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'daftar' => ['get', 'post'],
                    'biodata' => ['get'],
                    'biodata-save' => ['post'],
                    'berkas' => ['get', 'post'],
                    'berkas-upload' => ['post'],
                    'kuisioner-sosial' => ['get'],
                    'kuisioner-sosial-save' => ['post'],
                    'kuisioner-penyakit' => ['get'],
                    'kuisioner-penyakit-save' => ['post'],
                    'kuisioner-anamnesa' => ['get'],
                    'kuisioner-anamnesa-save' => ['post'],
                    'selesai' => ['get'],
                    'selesai-save' => ['post'],
                    'file' => ['get'],
                    'bukti-pendaftaran' => ['get'],
                    'photo' => ['get'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'captcha_daftar' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    function actionIndex()
    {
        $this->layout = "login_main";
        return $this->render('index');
    }
    function actionBiodata()
    {
        $model = User::find()->where(['u_id' => Yii::$app->user->identity->u_id])->limit(1)->one();
        $modelDetail = $model->userBrother ?? null;
        $modelNamaPasanganDetail = $model->namaPasangan ?? null;
        $modelNamaAnak = $model->namaAnak ?? null;

        $modelUserDetail = UserDetail::findOne(['no_rm' => $model->u_nik]);
        if (is_null($modelUserDetail)) {
            $modelUserDetail = new UserDetail();
        }


        $model->scenario = "peserta";
        $isRegClose = App::isRegClose() || App::isFinish();
        $pekerjaan = Pekerjaan::all();
        $agama = Agama::all();
        $pendidikan = [1 => 'Tidak Sekolah', 'TK' => 'TK', 'SD' => 'SD', 'SMP' => 'SMP', 'SMA' => 'SMA', 'D1' => 'D1', 'D2' => 'D2', 'D3' => 'D3', 'D4' => 'D4', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3'];
        return $this->render('biodata', [
            'model' => $model,
            'pekerjaan' => $pekerjaan,
            'agama' => $agama,
            'pendidikan' => $pendidikan,
            'isRegClose' => $isRegClose,
            'modelUserDetail' => $modelUserDetail,
            'modelDetail' => (empty($modelDetail)) ? [new UserBrother()] : $modelDetail,
            'namaPasanganDetail' => (empty($modelNamaPasanganDetail)) ? [new NamaPasangan()] : $modelNamaPasanganDetail,
            'modelNamaAnak' => (empty($modelNamaAnak)) ? [new MasterAnak()] : $modelNamaAnak,


        ]);
    }
    function actionBiodataSave()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $model = User::find()->where(['u_id' => Yii::$app->user->identity->u_id])->limit(1)->one();
            $modelUserDetail = UserDetail::findOne(['no_rm' => $model->u_nik]);
            $modelDetail = $model->userBrother ?? null;
            $modelNamaPasanganDetail = $model->namaPasangan ?? null;
            $modelNamaAnakDetail = $model->namaAnak ?? null;



            if (is_null($modelUserDetail)) {
                $modelUserDetail = new UserDetail();
            }

            $countID = UserBrother::find()->where(['nik' => $model->u_nik])->all();

            $countIDNamaPasangan = NamaPasangan::find()->where(['nik_pegawai' => $model->u_nik])->all();

            $countIdAnak = MasterAnak::find()->where(['nik_pegawai' => $model->u_nik])->all();
            // var_dump(count($countID) > 0 || count($countIDNamaPasangan) > 0 || count($countIdAnak) > 0);
            // exit;

            if (count($countID) > 0) {
                $oldIDs = ArrayHelper::map($modelDetail, 'id_user_brother', 'id_user_brother');
                $modelDetail = Model::createMultiple(UserBrother::classname(), $modelDetail, 'id_user_brother');
                Model::loadMultiple($modelDetail, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetail, 'id_user_brother', 'id_user_brother')));
            } else {
                $modelDetail = Model::createMultiple(UserBrother::classname());
                Model::loadMultiple($modelDetail, Yii::$app->request->post());
            }


            if (count($countIDNamaPasangan) > 0) {
                $oldIDPasangan = ArrayHelper::map($modelNamaPasanganDetail, 'id_nama_pasangan', 'id_nama_pasangan');
                $modelNamaPasanganDetail = Model::createMultiple(NamaPasangan::className(), $modelNamaPasanganDetail, 'id_nama_pasangan');
                Model::loadMultiple($modelNamaPasanganDetail, Yii::$app->request->post());
                $deletedIDsPasangan = array_diff($oldIDPasangan, array_filter(ArrayHelper::map($modelNamaPasanganDetail, 'id_nama_pasangan', 'id_nama_pasangan')));
            } else {
                $modelNamaPasanganDetail = Model::createMultiple(NamaPasangan::className());
                Model::loadMultiple($modelNamaPasanganDetail, Yii::$app->request->post());
            }

            if (count($countIdAnak) > 0) {
                $idAnakOld = ArrayHelper::map($modelNamaAnakDetail, 'id_anak', 'id_anak');
                $modelNamaAnakDetail = Model::createMultiple(NamaPasangan::className(), $modelNamaAnakDetail, 'id_anak');
                Model::loadMultiple($modelNamaAnakDetail, Yii::$app->request->post());
                $deletedIDsAnak = array_diff($idAnakOld, array_filter(ArrayHelper::map($modelNamaAnakDetail, 'id_anak', 'id_anak')));
            } else {
                $modelNamaAnakDetail = Model::createMultiple(MasterAnak::className());
                Model::loadMultiple($modelNamaAnakDetail, Yii::$app->request->post());
            }


            // $valid = $model->validate();
            // $valid = Model::validateMultiple($modelDetail) && $valid;


            $modelUserDetail->no_rm = $model->u_rm;
            $modelUserDetail->apakah_anda_anak_pertama = $_POST['UserDetail']['apakah_anda_anak_pertama'];
            $modelUserDetail->load($req->post());
            $model->scenario = "peserta";
            $model->load($req->post());
            $model->u_biodata_finish_at = date('Y-m-d H:i:s');
            if ($model->saveBiodata()) {

                $modelUserDetail->save();

                if (!empty($deletedIDs)) {
                    // UserBrother::deleteAll(['id_penerimaan_detail' => $deletedIDs]);
                    foreach ($deletedIDs as $key => $value) {
                        $deteledUserBrother = UserBrother::findOne($value);
                        $deteledUserBrother->delete();
                    }
                }
                // var_dump($deletedIDsPasangan);
                // exit;
                if (!empty($deletedIDsPasangan)) {
                    // UserBrother::deleteAll(['id_penerimaan_detail' => $deletedIDs]);
                    foreach ($deletedIDsPasangan as $key2 => $value2) {
                        $deteledUserBrother = NamaPasangan::findOne($value2);
                        $deteledUserBrother->delete();
                    }
                }

                if (!empty($deletedIDsAnak)) {
                    // UserBrother::deleteAll(['id_penerimaan_detail' => $deletedIDs]);
                    foreach ($deletedIDsPasangan as $key3 => $value3) {
                        $deleterNamaAnak = MasterAnak::findOne($value3);
                        $deleterNamaAnak->delete();
                    }
                }

                foreach ($modelNamaAnakDetail as $modelNamaAnakDetail) {
                    $modelNamaAnakDetail->nik_pegawai = $model->u_nik;
                    // $modelNamaAnakDetail->save(false);
                    var_dump($modelNamaAnakDetail->save());
                    exit;
                }

                foreach ($modelNamaPasanganDetail as $modelNamaPasanganDetail) {
                    $modelNamaPasanganDetail->nik_pegawai = $model->u_nik;
                    $modelNamaPasanganDetail->save(false);
                    // var_dump($modelNamaPasanganDetail->errors);
                }
                // exit;

                // untuk save detail ke tabel pengadaan_detail
                foreach ($modelDetail as $modelDetail) {
                    $modelDetail->nik = $model->u_nik;
                    $modelDetail->save();
                }
                $result = ['status' => true, 'msg' => 'Data berhasil disimpan'];
            } else {
                $result = ['status' => false, 'msg' => $modelUserDetail->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionBerkas()
    {
        $user = Yii::$app->user->identity;
        if ($user->u_biodata_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi biodata anda terlebih dahulu');
            return $this->redirect(['biodata']);
        }
        $berkas = Berkas::find()->with([
            'userberkas' => function ($q) use ($user) {
                $q->andWhere(['ub_user_id' => $user->u_id]);
            }
        ])->select('b_id,b_nama')->where(['b_status' => '1'])->asArray()->all();
        $isRegClose = App::isRegClose() || App::isFinish();
        return $this->render('berkas', [
            'user' => $user,
            'berkas' => $berkas,
            'isRegClose' => $isRegClose,
        ]);
    }
    function actionBerkasUpload()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $id = $req->post('id');
            $file = UploadedFile::getInstanceByName('berkas');
            $user_id = Yii::$app->user->identity->u_id;

            $model = UserBerkas::find()->where(['ub_user_id' => $user_id, 'ub_berkas_id' => $id])->limit(1)->one();
            if ($model == NULL) {
                $model = new UserBerkas();
                $model->ub_user_id = $user_id;
                $model->ub_berkas_id = $id;
            } else {
                $model->tmp_old_file = $model->ub_berkas;
            }
            $model->tmp_file = $file;
            if ($model->saveBerkas()) {
                $result = ['status' => true, 'msg' => 'Berkas berhasil diupload, <br><b>LIHAT KEMBALI BERKAS APAKAH SUDAH BENAR ATAU BELUM !</b>', 'id' => $model->ub_berkas_id];
            } else {
                $result = ['status' => false, 'msg' => $model->msg];
            }
            return $this->asJson($result);
        }
    }
    function actionKuisionerSosial()
    {
        $user = Yii::$app->user->identity;
        if ($user->u_biodata_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi biodata anda terlebih dahulu');
            return $this->redirect(['biodata']);
        }
        if ($user->u_berkas_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan upload berkas anda terlebih dahulu');
            return $this->redirect(['berkas']);
        }
        $model_biodata_tmp = UserKusionerBiodata::find()->where(['ukb_user_id' => $user->u_id])->orderBy(['ukb_created_at' => SORT_DESC])->limit(1)->one();
        $model_biodata = $model_biodata_tmp != NULL ? $model_biodata_tmp : new UserKusionerBiodata();
        $model_biodata->ukb_user_id = $user->u_id;
        if ($model_biodata->isNewRecord) {
            $model_biodata->is_sebelum = 'n';
            if ($user->u_pekerjaan == '011') {
                $model_biodata->is_sekarang = 'n';
            } else {
                $model_biodata->is_sekarang = 'y';
                $model_biodata->ukb_krj_skrg = $user->u_pekerjaan_nama;
                $model_biodata->ukb_krj_skrg_perusahaan = $user->u_alamat_pekerjaan;
            }
            $model_biodata->is_dituju = 'n';
            if ($user->u_jenis_mcu_id == 1) { //jika pre employee, cpns=>pns, magang=>tetap, maka pekerjaan dituju y
                $model_biodata->is_dituju = 'y';
            }
        } else {
            $model_biodata->is_sebelum = 'n';
            $model_biodata->is_sekarang = 'n';
            $model_biodata->is_dituju = 'n';
            if ($model_biodata->ukb_krj_sebelum != NULL) {
                $model_biodata->is_sebelum = 'y';
            }
            if ($model_biodata->ukb_krj_skrg != NULL) {
                $model_biodata->is_sekarang = 'y';
            }
            if ($model_biodata->ukb_krj_dituju != NULL) {
                $model_biodata->is_dituju = 'y';
            }
        }
        $kuisioner_sosial = Kuisioner::find()->where(['kk_id' => 4])->andWhere('k_id_parent IS NULL')->asArray()->all();
        $is_reg_close = App::isRegClose() || App::isFinish();
        return $this->render('kuisioner_sosial', [
            'model' => $model_biodata,
            'kuisioner_sosial' => $kuisioner_sosial,
            'is_reg_close' => $is_reg_close,
            'user' => $user,
        ]);
    }
    function actionKuisionerSosialSave()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $update = $req->post('update');
            $model = $update != NULL ? UserKusionerBiodata::findOne($update) : new UserKusionerBiodata();
            $model->load($req->post());
            if ($model->validate()) {
                if ($model->saveData($req->post())) {
                    $result = ['status' => true, 'msg' => 'Data berhasil disimpan', 'id' => $model->ukb_id];
                } else {
                    $result = ['status' => false, 'msg' => 'Data gagal disimpan, silahkan periksa kembali isian'];
                }
            } else {
                $result = ['status' => false, 'msg' => $model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionKuisionerPenyakit()
    {
        $user = Yii::$app->user->identity;
        if ($user->u_biodata_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi biodata anda terlebih dahulu');
            return $this->redirect(['biodata']);
        }
        if ($user->u_berkas_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan upload berkas anda terlebih dahulu');
            return $this->redirect(['berkas']);
        }
        if ($user->u_kuisioner1_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi kuisioner sosial anda terlebih dahulu');
            return $this->redirect(['kuisioner-sosial']);
        }
        $query_kategori_kuisioner = KategoriKuisioner::find()->where('kk_id != 4 and kk_id != 5');
        if ($user->u_jkel == 'L') {
            $query_kategori_kuisioner->andWhere('kk_id != 3');
        }
        $kategori_kuisioner = $query_kategori_kuisioner->asArray()->all();
        $is_reg_close = App::isRegClose() || App::isFinish();
        return $this->render('kuisioner_penyakit', [
            'user' => $user,
            'is_reg_close' => $is_reg_close,
            'kategori_kuisioner' => $kategori_kuisioner,
        ]);
    }
    function actionKuisionerPenyakitSave()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $data = $req->post();
            $m = new UserKusionerBiodata();
            if ($m->saveRiwayatPenyakit($data)) {
                $result = ['status' => true, 'msg' => 'Data berhasil disimpan'];
            } else {
                $result = ['status' => false, 'msg' => 'Data gagal disimpan, silahkan periksa kembali isian'];
            }
            return $this->asJson($result);
        }
    }
    function actionKuisionerAnamnesa()
    {
        $user = Yii::$app->user->identity;
        if ($user->u_biodata_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi biodata anda terlebih dahulu');
            return $this->redirect(['biodata']);
        }
        if ($user->u_berkas_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan upload berkas anda terlebih dahulu');
            return $this->redirect(['berkas']);
        }
        if ($user->u_kuisioner1_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi kuisioner sosial anda terlebih dahulu');
            return $this->redirect(['kuisioner-sosial']);
        }
        if ($user->u_kuisioner2_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi kuisioner sosial anda terlebih dahulu');
            return $this->redirect(['kuisioner-penyakit']);
        }
        $kategori_kuisioner_cpns = KategoriKuisioner::find()->where('kk_id = 5')->asArray()->limit(1)->all();
        $is_reg_close = App::isRegClose() || App::isFinish();
        return $this->render('kuisioner_anamnesa', [
            'user' => $user,
            'is_reg_close' => $is_reg_close,
            'kategori_kuisioner_cpns' => $kategori_kuisioner_cpns,
        ]);
    }
    function actionKuisionerAnamnesaSave()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $data = $req->post();
            $m = new UserKusionerBiodata();
            if ($m->saveRiwayatCpns($data)) {
                $result = ['status' => true, 'msg' => 'Data berhasil disimpan'];
            } else {
                $result = ['status' => false, 'msg' => 'Data gagal disimpan, silahkan periksa kembali isian'];
            }
            return $this->asJson($result);
        }
    }
    function actionSelesai()
    {
        $user = Yii::$app->user->identity;
        if ($user->u_biodata_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi biodata anda terlebih dahulu');
            return $this->redirect(['biodata']);
        }
        if ($user->u_berkas_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan upload berkas anda terlebih dahulu');
            return $this->redirect(['berkas']);
        }
        if ($user->u_kuisioner1_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi kusioner terlebih dahulu');
            return $this->redirect(['kuisioner-sosial']);
        }
        if ($user->u_kuisioner2_finish_at == NULL) {
            Yii::$app->session->setFlash('false', 'Silahkan lengkapi kusioner terlebih dahulu');
            return $this->redirect(['kuisioner-penyakit']);
        }
        if ($user->u_jenis_mcu_id == 1) {
            if ($user->u_kuisioner3_finish_at == NULL) {
                Yii::$app->session->setFlash('false', 'Silahkan lengkapi kusioner anamnesa terlebih dahulu');
                return $this->redirect(['kuisioner-anamnesa']);
            }
        }
        return $this->render('selesai', ['user' => $user]);
    }
    function actionSelesaiSave()
    {
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $model = User::find()->where(['u_id' => Yii::$app->user->identity->u_id])->limit(1)->one();
            if ($model->finishReg()) {
                Yii::$app->session->setFlash('success', 'Pendaftaran sudah selesai, silahkan download file bukti pendaftaran');
            } else {
                Yii::$app->session->setFlash('error', 'Maaf, terjadi kesalahan, silahkan hubungi administrator');
            }
            return $this->asJson(['status' => true]);
        }
    }

    function actionBuktiPendaftaran()
    {
        $user = User::find()->with([
            'jadwal', 'kuisionerbiodata',
            'jadwalperiksa' => function ($q) {
                $q->with(['permintaan' => function ($q) {
                    $q->with(['user']);
                }]);
            }
        ])->where(['u_id' => Yii::$app->user->identity->u_id])->asArray()->limit(1)->one();
        $kuisioner_sosial = Kuisioner::find()->where(['kk_id' => 4])->andWhere('k_id_parent IS NULL')->asArray()->all();
        $query_kategori_kuisioner = KategoriKuisioner::find()->where('kk_id != 4 and kk_id != 5');
        if (Yii::$app->user->identity->u_jkel == 'L') {
            $query_kategori_kuisioner->andWhere('kk_id != 3');
        }
        $kategori_kuisioner = $query_kategori_kuisioner->asArray()->all();
        $bukti = $this->renderPartial('pdf_bukti', ['user' => $user]);
        $persetujuan = $this->renderPartial('pdf_setuju', ['u' => $user]);
        $pelepasan = $this->renderPartial('pdf_lepas', ['u' => $user]);
        $kuisioner = $this->renderPartial('pdf_kuisioner', ['u' => $user, 'kuisioner_sosial' => $kuisioner_sosial, 'kategori_kuisioner' => $kategori_kuisioner]);
        $pdf = new Mpdf([
            'default_font' => 'Arial',
            'default_font_size' => 11
        ]);
        $pdf->tMargin = 10;
        $pdf->simpleTables = true;
        $pdf->packTableData = true;
        $pdf->useSubstitutions = false;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit = 1;

        $pdf->AddPageByArray([
            'orientation' => 'P',
            'sheet-size' => [210, 150],
            'margin-top' => 10,
        ]);
        $pdf->SetHTMLFooter('<small style="font-size:10px;"><i>dicetak pada : ' . date('d-m-Y H:i') . '</i></small>', 'O');
        $pdf->WriteHTML($bukti);

        $pdf->AddPageByArray([
            'orientation' => 'P',
            'sheet-size' => 'A4',
            'margin-top' => 20
        ]);
        $pdf->WriteHTML($persetujuan);

        $pdf->AddPageByArray([
            'orientation' => 'P',
            'sheet-size' => 'A4',
        ]);
        $pdf->WriteHTML($pelepasan);

        $pdf->AddPageByArray([
            'orientation' => 'P',
            'sheet-size' => 'A4',
        ]);
        $pdf->WriteHTML($kuisioner);

        if (in_array($user['u_debitur_id'], Yii::$app->params['kuisioner']['cpns'])) {
            $kuisioner_cpns = UserKuisioner::find()->where(['u_id' => $user['u_id']])->joinWith([
                'kuisioner' => function ($q) {
                    $q->andWhere(['kk_id' => 5]);
                }
            ])->asArray()->all();
            // echo "<pre>"; print_r($kuisioner_cpns);
            $cpns = $this->renderPartial('pdf_cpns', ['u' => $user, 'kuisioner_cpns' => $kuisioner_cpns]);
            $pdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size' => 'A4',
            ]);
            $pdf->WriteHTML($cpns);
        }
        $pdf->Output('LAPORAN HASIL PENGUJIAN_' . date('d-m-Y H:i:s') . '.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
    function actionInformasi()
    {
        $data = Informasi::find()->where(['i_jenis' => 3])->orderBy(['i_urut' => SORT_ASC])->asArray()->all();
        Yii::$app->db->createCommand()->update(User::tableName(), ['u_read_doc_at' => date('Y-m-d H:i:s')], ['u_id' => Yii::$app->user->identity->u_id])->execute();
        return $this->render('informasi', ['data' => $data]);
    }
    function actionFile($data)
    {
        $user_id = Yii::$app->user->identity->u_id;
        $model = UserBerkas::find()->where(['ub_user_id' => $user_id, 'ub_berkas_id' => $data])->asArray()->limit(1)->one();
        if ($model != NULL) {
            return Yii::$app->response->sendFile(Yii::$app->params['storage'] . $model['ub_berkas'], $model['ub_berkas'], ['inline' => true]);
        }
        Yii::$app->session->setFlash('error', 'Berkas tidak ditemukan');
        return $this->redirect(['index']);
    }
    function actionPhoto($rm)
    {
        $photo = UserBerkas::find()->joinWith([
            'user',
            'berkas'
        ], false)->where(['u_rm' => sprintf('%08d', $rm), 'ub_berkas_id' => 1])->asArray()->limit(1)->one();
        if ($photo != NULL) {
            if (file_exists(Yii::$app->params['storage'] . $photo['ub_berkas']) && is_file(Yii::$app->params['storage'] . $photo['ub_berkas'])) {
                return Yii::$app->response->sendFile(Yii::$app->params['storage'] . $photo['ub_berkas'], $photo['ub_berkas'], ['inline' => true]);
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    function actionOffline()
    {
        return $this->render('offline');
    }



    function actionListPeserta($tgl = NULL)
    {
        $jadwal = Jadwal::find()->where('j_tgl <= :tgl', [':tgl' => date('Y-m-d')])->asArray()->all();
        $user = User::find()->select('u_nik,u_rm,u_nama_depan,u_nama_belakang,u_finish_at,u_jadwal_asli_id,u_jadwal_id,ja.j_tgl as tgl_asli,j.j_tgl as tgl_baru')
            ->joinWith(['jadwal j', 'jadwalasli ja'], false)
            ->where('ja.j_tgl = :tgl and u_debitur_id is null', [':tgl' => $tgl != NULL ? $tgl : '2020-09-01'])
            ->orderBy(['u_nama_depan' => SORT_ASC])->asArray()->all();

        $user_tidak = User::find()->joinWith(['jadwal'], false)->where('u_rm is null and j_tgl = :tgl', [':tgl' => $tgl])->orderBy(['u_nama_depan' => SORT_ASC])->asArray()->all();
        return $this->render('list_peserta', [
            'user' => $user,
            'user_tidak' => $user_tidak,
            'jadwal' => $jadwal,
            'tgl' => $tgl
        ]);
    }
    function actionPesertaPdf($tgl)
    {
        $user = User::find()->select('u_nik,u_rm,u_nama_depan,u_nama_belakang,u_finish_at,u_jadwal_asli_id,u_jadwal_id,ja.j_tgl as tgl_asli,j.j_tgl as tgl_baru')
            ->joinWith(['jadwal j', 'jadwalasli ja'], false)
            ->where('ja.j_tgl = :tgl', [':tgl' => $tgl])
            ->orderBy(['u_nama_depan' => SORT_ASC])->asArray()->all();
        $content = $this->renderPartial('pdf_peserta', ['user' => $user, 'tgl' => $tgl]);
        $pdf = new Mpdf([
            'default_font' => 'Arial',
            'default_font_size' => 10
        ]);
        $pdf->tMargin = 10;
        $pdf->simpleTables = true;
        $pdf->packTableData = true;
        $pdf->useSubstitutions = false;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit = 1;

        $pdf->AddPageByArray([
            'orientation' => 'P',
            'sheet-size' => 'A4',
            'margin-left' => 10,
            'margin-right' => 10,
        ]);
        $pdf->WriteHTML($content);

        $pdf->Output('LAPORAN HASIL PENGUJIAN_' . date('d-m-Y H:i:s') . '.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
}
