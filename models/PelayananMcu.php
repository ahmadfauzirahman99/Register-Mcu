<?php
namespace app\models;
use Yii;
class PelayananMcu extends \yii\db\ActiveRecord
{   
    public static function tableName()
    {
        return 'mcu.data_pelayanan';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbpost');
    }
    static function all()
    {
        return self::find()->asArray()->all();
    }
    function savePelayanan($obj)
    {
		$status_nikah=['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda'];
		$pendidikan=[1=>'Tidak Sekolah','TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3'];
		$this->id_data_pelayanan=str_replace('.','',microtime(true));
		$this->no_rekam_medik=$obj->u_rm;
		$this->no_mcu=$obj->u_rm;
		$this->nama=$obj->u_nama_depan.' '.$obj->u_nama_belakang;
		$this->tempat=$obj->u_tmpt_lahir;
		$this->tgl_lahir=date('Y-m-d H:i:s',strtotime($obj->u_tgl_lahir));
		$this->agama=Agama::find()->where(['Kode'=>$obj->u_agama])->asArray()->limit(1)->one()['Agama'];
		$this->kedudukan_dalam_keluarga=$obj->u_kedudukan_keluarga;
		$this->status_perkawinan=$status_nikah[$obj->u_status_nikah];
		$this->pendidikan=$pendidikan[$obj->u_pendidikan];
		$this->pekerjaan=$obj->u_pekerjaan_nama;
		$this->alamat=$obj->u_alamat;
		$this->wni="Indonesia";
		if($obj->jadwalperiksa!=NULL){
			$this->tanggal_pemeriksaan=$obj->jadwalperiksa->upj_tgl;
		}
		$this->jenis_kelamin=$obj->u_jkel;
		$this->no_ujian=$obj->u_nik;
		$this->kode_debitur=$obj->u_debitur_id;
		$this->kode_paket=$obj->u_paket_id;
		if($this->save(false)){
			return true;
		}else{
			return false;
		}
	}
	function savePelayananUmum($obj)
    {
		$status_nikah=['K'=>'Kawin','T'=>'Belum Kawin','J'=>'Janda','D'=>'Duda'];
		$pendidikan=[1=>'Tidak Sekolah','TK'=>'TK','SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3','D4'=>'D4','S1'=>'S1','S2'=>'S2','S3'=>'S3'];
		$this->id_data_pelayanan=str_replace('.','',microtime(true));
		$this->no_rekam_medik=$obj->u_rm;
		$this->no_mcu=$obj->u_rm;
		$this->nama=$obj->u_nama_depan;
		$this->tempat=$obj->u_tmpt_lahir;
		$this->tgl_lahir=date('Y-m-d H:i:s',strtotime($obj->u_tgl_lahir));
		$this->agama=Agama::find()->where(['Kode'=>$obj->u_agama])->asArray()->limit(1)->one()['Agama'];
		$this->kedudukan_dalam_keluarga=$obj->u_kedudukan_keluarga;
		$this->status_perkawinan=$status_nikah[$obj->u_status_nikah];
		$this->pendidikan=$pendidikan[$obj->u_pendidikan];
		$this->pekerjaan=$obj->u_pekerjaan_nama;
		$this->alamat=$obj->u_alamat;
		$this->wni="Indonesia";
		$this->tanggal_pemeriksaan=$obj->u_tgl_periksa;
		$this->jenis_kelamin=$obj->u_jkel;
		$this->no_ujian=$obj->u_nik;
		$this->kode_debitur=$obj->u_debitur_id;
		$this->kode_paket=$obj->u_paket_id;
		if($this->save(false)){
			return true;
		}else{
			return false;
		}
	}
	function updateData()
	{
		
	}
}
