<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class UserDaftar extends ActiveRecord implements IdentityInterface
{
    public $captcha;
    public $pendidikan = [1 => 'Tidak Sekolah', 'TK' => 'TK', 'SD' => 'SD', 'SMP' => 'SMP', 'SMA' => 'SMA', 'D1' => 'D1', 'D2' => 'D2', 'D3' => 'D3', 'D4' => 'D4', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3'];
    public static function tableName()
    {
        return 'user_daftar';
    }
    public function rules()
    {
        return [
            [['ud_nik', 'ud_nama', 'ud_email', 'ud_tgl_lahir', 'captcha'], 'required', 'on' => 'umum_daftar', 'message' => '{attribute} harus diisi'],
            [['ud_nik', 'ud_nama', 'ud_email', 'ud_jkel', 'ud_telp', 'ud_tmpt_lahir', 'ud_tgl_lahir', 'ud_alamat', 'ud_rt', 'ud_rw', 'ud_provinsi', 'ud_kabupaten', 'ud_kecamatan', 'ud_pekerjaan', 'ud_agama', 'ud_nama_ayah', 'ud_nama_ibu', 'ud_status_nikah', 'ud_kedudukan_keluarga', 'ud_pendidikan'], 'required', 'on' => 'update_akun_as_peserta', 'message' => '{attribute} harus diisi'],
            [['ud_email', 'ud_telp', 'ud_alamat', 'ud_rt', 'ud_rw', 'ud_provinsi', 'ud_kabupaten', 'ud_kecamatan', 'ud_pekerjaan', 'ud_agama', 'ud_nama_ayah', 'ud_nama_ibu', 'ud_status_nikah', 'ud_kedudukan_keluarga', 'ud_pendidikan'], 'required', 'on' => 'update_akun_as_pasien', 'message' => '{attribute} harus diisi'],
            [['ud_approve_status'], 'required', 'on' => 'update_status_as_admin', 'message' => '{attribute} harus diisi'],
            [['ud_ktp'], 'required', 'on' => 'upload_ktp'],
            [['ud_email'], 'email'],
            [['ud_email', 'ud_nik'], 'unique'],
            ['ud_nik', 'checkNikPasien', 'on' => 'umum_daftar'],
            [['ud_tgl_lahir', 'ud_created_at', 'ud_auth_key', 'ud_approve_at'], 'safe'],
            [['ud_nik', 'ud_rm'], 'string', 'max' => 50],
            [['ud_approve_ket', 'ud_approve_status', 'ud_jkel', 'ud_status_nikah', 'ud_anggota_darurat', 'ud_anggota_darurat_ket', 'ud_is_pasien_baru'], 'string'],
            [['ud_email', 'ud_nama', 'ud_alamat', 'ud_tmpt_lahir', 'ud_nama_ayah', 'ud_nama_ibu', 'ud_provinsi', 'ud_kabupaten', 'ud_kecamatan', 'ud_kedudukan_keluarga', 'ud_nama_pasangan', 'ud_jabatan_pekerjaan', 'ud_pendidikan', 'ud_alamat_pekerjaan', 'ud_tempat_tugas'], 'string', 'max' => 255],
            [['ud_rt', 'ud_rw', 'ud_pendidikan', 'ud_pekerjaan'], 'string', 'max' => 5],
            [['ud_agama', 'ud_istri_ke', 'ud_approve_by'], 'integer'],
            [['ud_telp'], 'string', 'max' => 20],
        ];
    }
    public function attributeLabels()
    {
        return [
            'ud_id' => 'ID',
            'ud_rm' => 'No. Rekam Medis',
            'ud_nik' => 'NIK',
            'ud_nama' => 'Nama Lengkap',
            'ud_email' => 'Email',
            'ud_alamat' => 'Alamat',
            'ud_rt' => 'RT',
            'ud_rw' => 'RW',
            'ud_provinsi' => 'Provinsi',
            'ud_kabupaten' => 'Kabupaten/Kota',
            'ud_kecamatan' => 'Kecamatan',
            'ud_pekerjaan' => 'Pekerjaan',
            'ud_jabatan_pekerjaan' => 'Jabatan Pekerjaan',
            'ud_tempat_tugas' => 'Nama Perusahaan/Instansi Tempat Bekerja',
            'ud_alamat_pekerjaan' => 'Alamat Perusahaan/Instansi Tempat Bekerja',
            'ud_agama' => 'Agama',
            'ud_jkel' => 'Jenis Kelamin',
            'ud_telp' => 'No. Telp/Hp',
            'ud_pendidikan' => 'Pendidikan',
            'ud_status_nikah' => 'Status Pernikahan',
            'ud_kedudukan_keluarga' => 'Kedudukan Dalam Keluarga',
            'ud_nama_pasangan' => 'Nama Pasangan',
            'ud_istri_ke' => 'Istri Ke',
            'ud_approve_status' => 'Status Persetujuan',
            'ud_approve_ket' => 'Keterangan Persetujuan',
            'ud_nama_ayah' => 'Nama Ayah',
            'ud_nama_ibu' => 'Nama Ibu',
            'ud_anggota_darurat' => 'Anda Termasuk Tim Penangangan Keadaan Darurat',
            'ud_anggota_darurat_ket' => 'Nama Tim Penangangan Keadaan Darurat',
            'captcha' => 'Captcha',
            'ud_tmpt_lahir' => 'Tempat Lahir',
            'ud_tgl_lahir' => 'Tanggal Lahir',
            'ud_pekerjaan' => 'Pekerjaan',
            'ud_created_at' => 'Tanggal Daftar',
            'ud_is_pasien_baru' => 'Pasien Baru Atau Lama ?'
        ];
    }
    function beforeSave($model)
    {
        $this->ud_tgl_lahir = date('Y-m-d', strtotime($this->ud_tgl_lahir));
        if ($this->scenario == "update_akun_as_peserta" || $this->scenario == "update_akun_as_pasien") {
            $user = Yii::$app->user->identity;
            if ($this->ud_status_nikah == 'B' || $this->ud_status_nikah == 'J' || $this->ud_status_nikah == 'D') {
                $this->ud_istri_ke = NULL;
                $this->ud_nama_pasangan = NULL;
                if ($this->ud_status_nikah == 'B') {
                    $this->ud_kedudukan_keluarga = "anak";
                }
            }
            if ($this->ud_kedudukan_keluarga != 'istri') {
                $this->ud_istri_ke = NULL;
            }
            if (!isset($user->ud_id)) {
                $this->ud_approve_status = '2';
            }
            if ($this->scenario == "update_akun_as_pasien") {
                $this->ud_rm = $user->NO_PASIEN;
                $this->ud_is_pasien_baru = 'n';
                $this->ud_created_at = date('Y-m-d H:i:s');
            }
            $this->ud_update_biodata_at = date('Y-m-d H:i:s');
        }
        if ($this->scenario == "umum_daftar") {
            $this->ud_created_at = date('Y-m-d H:i:s');
        }
        if ($this->scenario == "update_status_as_admin") {
            if ($this->ud_approve_status == '2') {
                $this->ud_approve_ket = NULL;
            }
            $this->ud_approve_by = Yii::$app->user->identity->u_id;
            $this->ud_approve_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($model);
    }
    static function findByUsername($nik, $tgl_lahir)
    {
        return static::find()->where(['or', ['ud_nik' => trim($nik)], ['ud_rm' => trim($nik)]])->andWhere(['ud_tgl_lahir' => date('Y-m-d', strtotime($tgl_lahir))])->limit(1)->one();
    }
    public static function findIdentity($id)
    {
        return static::findOne(['ud_id' => $id])->andWhere("ud_approve_status is null")->limit(1)->one();
    }
    public function getId()
    {
        return $this->ud_id;
    }
    public function getAuthKey()
    {
        return $this->ud_auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->ud_auth_key === $authKey;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    public function checkNikPasien($attribute, $params)
    {
        $count = Pasien::find()->where(['NOIDENTITAS' => $this->ud_nik])->count();
        if ($count > 0) {
            $this->addError($attribute, 'Anda pernah melakukan pemeriksaan di RSUD, silahkan login menggunakan data anda');
        }
    }
    function getAgama()
    {
        return $this->hasOne(Agama::className(), ['Kode' => 'ud_agama']);
    }
    function getPekerjaan()
    {
        return $this->hasOne(Pekerjaan::className(), ['Nomor' => 'ud_pekerjaan']);
    }
    function setDataByPasien()
    {
        $u = Yii::$app->user->identity;
        $this->ud_rm = $u->NO_PASIEN;
        $this->ud_nik = $u->NOIDENTITAS;
        $this->ud_nama = $u->NAMA;
        $this->ud_jkel = $u->JENIS_KEL;
        $this->ud_tmpt_lahir = $u->TP_LAHIR;
        $this->ud_tgl_lahir = date('d-m-Y', strtotime($u->TGL_LAHIR));
        $this->ud_alamat = $u->ALAMAT;
        $this->ud_rt = $u->RT;
        $this->ud_rw = $u->RW;
        $this->ud_telp = $u->NO_HP;
        $this->ud_provinsi = $u->PROPINSI;
        $this->ud_kabupaten = $u->KABUPATEN;
        $this->ud_kecamatan = $u->KECAMATAN;
        $this->ud_nama_ayah = $u->NAMAAYAH;
        $this->ud_nama_ibu = $u->NAMAIBU;
    }
    function approvePesertaAsPasien()
    {
        $trans_pasien = Pasien::getDb()->beginTransaction();
        $transaction = self::getDb()->beginTransaction();
        try {
            // if($this->ud_approve_status=='2'){
            //     if(!empty($this->ud_rm)){
            //         $field="NO_PASIEN";
            //         $value=sprintf('%08d',$this->ud_rm);
            //     }else{
            //         $field="NOIDENTITAS";
            //         $value=$this->ud_nik;
            //     }
            //     $checkPasien=Pasien::findOne([$field=>$value]);
            //     $pasien = $checkPasien==NULL ? new Pasien() : $checkPasien;
            //     try {
            //         if(!$pasien->savePesertaAsPasien($this)){
            //             $transaction->rollBack();
            //             return false;
            //         }
            //         $this->ud_rm=$pasien->NO_PASIEN;
            //     }catch(\Exception $e){
            //         $trans_pasien->rollBack();
            //         throw $e;
            //     }catch(\Throwable $e){
            //         $trans_pasien->rollBack();
            //         throw $e;
            //     }
            // }
            $this->save(false);
            $transaction->commit();
            $trans_pasien->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return false;
    }
}
