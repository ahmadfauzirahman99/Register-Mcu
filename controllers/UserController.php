<?php
namespace app\controllers;
use Yii;
use app\models\Formasi;
use app\models\FormasiSyarat;
use app\models\JenisPendidikan;
use app\models\User;
use app\models\UserSoal;
use app\models\UserBerkas;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['nilai-index','nilai-import','nilai-report-form','rekap-form','rekap-all','index','create','view','status-form','status-save','file','get-formasi','report-form','report'],
                        'allow' => true,
                        'matchCallback'=>function(){
                            if(!Yii::$app->user->isGuest){
                                if(Yii::$app->user->identity->isAdm()){
                                    return true;    
                                }
                            }
                            return false;
                        }
                    ],
					[
						'actions'=>['import'],
						'allow' => true,
						'roles'=>['?']
					],
					[
						'actions'=>['update-data','check-data'],
						'allow'=>true,
						'roles'=>['@']
					]
                ],
                'denyCallback' => function ($rule, $action)
                {
                    Yii::$app->session->setFlash('error','Session sudah habis, silahkan login kembali');
                    $url=Yii::$app->urlManager->createUrl('/auth/index');
                    return $this->redirect($url);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
					'import'=>['get','post'],
                    'file'=>['get'],
                    'delete' => ['POST'],
                    'status-form' => ['post'],
                    'status-save' => ['post'],
                    'get-formasi' => ['post'],
                    'report-formi' => ['post'],
                    'report' => ['post'],
					'rekap-form'=>['post'],
                    'rekap-all'=>['post'],
                    'nilai-index'=>['get'],
                    'nilai-import'=>['post'],
                    'nilai-report-form'=>['post'],
                ],
            ],
        ];
    }
	function actionImport()
	{
		$req=Yii::$app->request;
		if($req->isPost){
			$file=UploadedFile::getInstanceByName('import');
			$spreadsheet = IOFactory::load($file->tempName);
			$data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			$model = new User();
			if($model->importData($data)){
				echo "ok";
			}else{
				echo "no";
			}
		}
		return $this->render('import');
	}
	function actionUpdateData(){
     $data=\app\models\User::find()->select('u_rm,u_jadwal_id')->where('u_rm is not null and u_jadwal_id = 5')->asArray()->all();
	if(count($data)>0){
		echo "<pre>";
		foreach($data as $d){
			
			$update_mcu = \app\models\PelayananMcu::find()->select('id_data_pelayanan,tanggal_pemeriksaan')->where(['no_rekam_medik'=>$d['u_rm']])->andWhere("tanggal_pemeriksaan::text NOT LIKE '%2020-09-05%'")->limit(1)->one();
			if($update_mcu!=NULL){
				echo "<br>".$d['u_rm'].' '.$d['u_jadwal_id']." = ".$update_mcu->tanggal_pemeriksaan."<br>";
				//$update_mcu->tanggal_pemeriksaan=date('Y-m-d',strtotime('2020-09-05'));
				//$update_mcu->save(false);
				echo $update_mcu->tanggal_pemeriksaan."<br>";
				
				//print_r($update_mcu);
				//break;
			}
			
		}
	}
}
	function actionCheckData()
	{
		$data=\app\models\User::find()->select('u_nik,u_rm,u_jadwal_id,u_jkel')->where('u_rm is not null')->asArray()->all();
		//echo count($data)."<br>";
		$i=1;
		foreach($data as $d){
			//$pasien=\app\models\Pasien::find()->select('NO_PASIEN')->where(['NOIDENTITAS'=>$d['u_nik']])->asArray()->all();
			$up=\app\models\PelayananMcu::find()->where(['no_rekam_medik'=>$d['u_rm']])->limit(1)->one();
			if($up!=NULL){
				//$up->jenis_kelamin=$d['u_jkel'];
				//$up->save(false);
			}
		}
		//echo $i;
	}
    function actionNilaiIndex()
    {
        $searchModel = new UserSearch();
        // $searchModel->simpanTotalNilai();
        $dataProvider = $searchModel->searchNilai(Yii::$app->request->queryParams);
        $formasi=Formasi::allByJenisAdmin();
        $pendidikan=JenisPendidikan::find()->asArray()->all();
		$verifikator=User::find()->select('u_id,u_nama')->where(['u_level'=>'1'])->asArray()->all();
        return $this->render('nilai_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'formasi'=>$formasi,
            'pendidikan'=>$pendidikan,
			'verifikator'=>$verifikator,
        ]);
    }
    function actionNilaiImport()
    {
        $file=UploadedFile::getInstanceByName('berkas');
        $spreadsheet = IOFactory::load($file->tempName);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if(count($data)>0){
            $model = new UserSoal();
            if($model->importJawaban($data)){
                $result=['status'=>true,'msg'=>'Jawaban berhasil diimport'];
            }else{
                $result=['status'=>false,'msg'=>$model->errorMsg];
            }
        }else{
            $result=['status'=>false,'msg'=>'Data tidak tersedia'];
        }
    }
    function actionNilaiReportForm($id)
    {
        $req=Yii::$app->request;;
        if($req->isAjax){
			$formasi=Formasi::find()->where("f_id != 8")->select(["f_id as id","concat(jp_nama,' - ',f_nama_formasi,' (',f_pendidikan,')') as nama"])->joinWith(['jpendidikan'],false)->asArray()->all();
            $formasi=array_map(function($q){
				if($q['id']==4){
					return ['id'=>$q['id'],'nama'=>str_replace('D III - ','',$q['nama'])];
				}
				return $q;
			},$formasi);
			return $this->renderAjax('nilai_report_form',[
                'formasi'=>$formasi,
            ]);
        }
    }
	function actionRekapForm()
	{
		$req=Yii::$app->request;;
        if($req->isAjax){
			$formasi=Formasi::find()->where("f_id != 8")->select(["f_id as id","concat(jp_nama,' - ',f_nama_formasi,' (',f_pendidikan,')') as nama"])->joinWith(['jpendidikan'],false)->asArray()->all();
            $formasi=array_map(function($q){
				if($q['id']==4){
					return ['id'=>$q['id'],'nama'=>str_replace('D III - ','',$q['nama'])];
				}
				return $q;
			},$formasi);
			return $this->renderAjax('rekap_form',[
                'formasi'=>$formasi,
            ]);
        }
	}
	function actionRekapAll()
	{
		$data=User::rekapAll();
		$content=$this->renderpartial('rekap_pdf',['data'=>$data]);
		$pdf = new Mpdf([
            'mode' => 'utf-8',
            'format'=>'Legal-L',
            'default_font' => 'Arial',
            'default_font_size' => 9
        ]);
        $pdf->useSubstitutions=false;
        $pdf->simpleTables=true;
        $pdf->packTableData=true;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit=0;
        $pdf->showImageErrors = true;
        $pdf->AddPageByArray([
            'margin-left'=>20,
            'margin-top'=>13,
            'margin-right'=>20,
            'margin-bottom'=>20,
            'mirrorMargins'=>true,
        ]);
        $pdf->SetTitle('REKAPITULASI PENDAFTARAN');
        $pdf->SetCreator('RSUD ARIFIN ACHMAD');
        $pdf->SetHeader('RSUD ARIFIN ACHMAD - REKAPITULASI PENDAFTARAN');
        $pdf->SetFooter('{PAGENO}');
        $pdf->WriteHTML($content);
        $pdf->Output("REKAPITULASI_PENDAFTARAN.pdf",\Mpdf\Output\Destination::INLINE);
	}
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $formasi=Formasi::allByJenisAdmin();
        $pendidikan=JenisPendidikan::find()->asArray()->all();
		$verifikator=User::find()->select('u_id,u_nama')->where(['u_level'=>'1'])->asArray()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'formasi'=>$formasi,
            'pendidikan'=>$pendidikan,
			'verifikator'=>$verifikator,
        ]);
    }
    function actionGetFormasi()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $data=Formasi::allByJenisAdmin($id);
            return $this->asJson(['data'=>$data]);
        }
    }
    public function actionView($id)
    {
        $model = User::find()->alias('u')->joinWith([
            'jenispendidikan jp',
            'formasipendidikan fp',
            'formasi'
        ],false)->select('u.*,jp.jp_nama as jenis_pendidikan,fp.jp_nama as formasi_pendidikan,f_nama_formasi,f_pendidikan')->where(['u_id'=>$id])->asArray()->limit(1)->one();
        $berkas_syarat=FormasiSyarat::find()->with([
            'jenisBerkas'=>function($q){ 
                $q->select('jb_id,jb_nama'); 
            },
            'userBerkas'=>function($q) use($model){ 
                $q->select('ub_id,ub_formasi_syarat_id,ub_berkas')->andWhere(['ub_user_id'=>$model['u_id']]); 
            }
        ])->where(['fs_formasi_id'=>$model['u_formasi_id']])->asArray()->all();
		
		$verifikator=NULL;
		if($model['u_verify_by']!=NULL){
			$query_verifikator=User::find()->where(['u_id'=>$model['u_verify_by']])->asArray()->limit(1)->one();
			if($query_verifikator!=NULL){
				$verifikator=$query_verifikator['u_nama'];
			}
		}
		return $this->render('view', [
            'model' => $model,
            'berkas_syarat'=>$berkas_syarat,
			'verifikator'=>$verifikator,
        ]);
    }
    function actionFile($id)
    {
        $model = UserBerkas::find()->select('ub_berkas')->where(['ub_id'=>$id])->asArray()->limit(1)->one();
        if($model!=NULL){
            return Yii::$app->response->sendFile(Yii::$app->params['storage'].$model['ub_berkas'], $model['ub_berkas'], ['inline'=>true]);
        }
        Yii::$app->session->setFlash('error','Berkas tidak ditemukan');
        return $this->redirect(['index']);
    }
    function actionStatusForm()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = User::find()->where(['u_id'=>$id])->limit(1)->one();
            return $this->renderAjax('status',[
                'model'=>$model,
            ]);
        }
    }
    function actionStatusSave()
    {
        $req=Yii::$app->request;
        if($req->isAjax){
            $id=$req->post('id');
            $model = User::find()->select('u_id,u_lulus_reg,u_ket')->where(['u_id'=>$id])->limit(1)->one();
            if(empty($model->u_lulus_reg)){
                $model->u_lulus_reg=null;
            }
            $model->load($req->post());
			$model->u_verify_by=Yii::$app->user->identity->u_id;
			$model->u_verify_at=date('Y-m-d H:i:s');
            if($model->save()){
                $result=['status'=>true,'msg'=>'Status kelulusan berhasil disimpan'];
            }else{
                $result=['status'=>false,'msg'=>$model->errors];
            }
            return $this->asJson($result);
        }
    }
    function actionReportForm()
    {
        $req=Yii::$app->request;;
        if($req->isAjax){
            //$formasi=Formasi::allByJenisAdmin();
			$formasi=Formasi::find()->where("f_id != 8")->select(["f_id as id","concat(jp_nama,' - ',f_nama_formasi,' (',f_pendidikan,')') as nama"])->joinWith(['jpendidikan'],false)->asArray()->all();
            $formasi=array_map(function($q){
				if($q['id']==4){
					return ['id'=>$q['id'],'nama'=>str_replace('D III - ','',$q['nama'])];
				}
				return $q;
			},$formasi);
			return $this->renderAjax('report_form',[
                'formasi'=>$formasi,
            ]);
        }
    }
    function actionReport()
    {
        $req=Yii::$app->request;
        $status=$req->post('status');
        $formasi=$req->post('formasi');
        $perawat=$req->post('perawat');

        $query=User::find()->where(['u_level'=>'2','u_finish_reg'=>'1'])->with(['formasi']);
		if($status!=NULL){
			$query->andWhere(['u_lulus_reg'=>$status]);
		}
        if($formasi!=NULL){
			if($formasi==4){
				$query->andWhere(['in','u_formasi_id',[4,8]]);
			}else{
				if(!empty($perawat)){
					$query->andWhere(['u_jalur_perawat'=>$perawat]);
				}
				$query->andWhere(['u_formasi_id'=>$formasi]);
			}
		}
		if($formasi==9){
			$query->orderBy(['u_lulus_reg'=>'DESC NULLS LAST','u_ipk'=>SORT_DESC]);
		}else{
			$query->orderBy(['u_lulus_reg'=>'DESC NULLS LAST','u_ipk'=>SORT_DESC]);
		}
        $user=$query->asArray()->all();
		$data_formasi=Formasi::find()->select(["concat(jp_nama,' - ',f_nama_formasi,' (',f_pendidikan,')') as nama"])->where(['f_id'=>$formasi])->joinWith(['jpendidikan'],false)->asArray()->limit(1)->one();
        $content= $this->renderPartial('report_pdf',['user'=>$user,'status'=>$status,'data_formasi'=>$data_formasi,'perawat'=>$perawat]);
		
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format'=>'Legal-L',
            'default_font' => 'Arial',
            'default_font_size' => 9
        ]);
        $pdf->useSubstitutions=false;
        $pdf->simpleTables=true;
        $pdf->packTableData=true;
        $pdf->autoPageBreak = true;
        $pdf->shrink_tables_to_fit=0;
        $pdf->showImageErrors = true;
        $pdf->AddPageByArray([
            'margin-left'=>10,
            'margin-top'=>13,
            'margin-right'=>10,
            'margin-bottom'=>20,
            'mirrorMargins'=>true,
        ]);
        $pdf->SetTitle('REKAPITULASI PENDAFTARAN');
        $pdf->SetCreator('RSUD ARIFIN ACHMAD');
        $pdf->SetHeader('RSUD ARIFIN ACHMAD - REKAPITULASI PENDAFTARAN');
        $pdf->SetFooter('{PAGENO}');
        $pdf->WriteHTML($content);
        $pdf->Output("REKAPITULASI_PENDAFTARAN.pdf",\Mpdf\Output\Destination::INLINE);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
