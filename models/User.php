<?php

namespace app\models;

use Yii;
use app\widgets\App;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $captcha, $old_formasi, $error_msg, $nama, $username, $password, $is_riwayat_mcu, $ktp;
    public static function tableName()
    {
        return 'user';
    }
    public function rules()
    {
        return [
            //done by instansi
            [['username', 'u_password', 'u_nama_depan', 'u_alamat', 'captcha'], 'required', 'on' => 'create_akun_instansi', 'message' => '{attribute} harus diisi'],
            [['username', 'u_nama_depan', 'u_nama_petugas', 'u_alamat', 'u_no_hp'], 'required', 'on' => 'update_akun_instansi', 'message' => '{attribute} harus diisi'],
            [['captcha'], 'captcha', 'on' => 'create_akun_instansi', 'captchaAction' => 'auth/captcha_daftar'],
            [['u_nik', 'u_nama_depan', 'u_jkel', 'u_tmpt_lahir', 'u_tgl_lahir', 'u_jabatan_pekerjaan', 'u_paket_id'], 'required', 'on' => 'create_peserta_by_instansi', 'message' => '{attribute} harus diisi'],
            [['u_nik', 'u_nama_depan', 'u_tmpt_lahir', 'u_tgl_lahir', 'u_pekerjaan', 'u_jabatan', 'u_tempat_tugas'], 'required', 'on' => 'insert_by_excel', 'message' => '{attribute} harus diisi'],

            //peserta
            [['u_jenis_mcu_id', 'u_tgl_periksa', 'is_riwayat_mcu', 'u_paket_id'], 'required', 'on' => 'peserta_daftar', 'message' => '{attribute} harus diisi'],
            ['u_tgl_periksa', 'validateTanggalPeriksa'],

            //verify_by_rm
            [['u_approve_status'], 'required', 'on' => 'verify_by_rm'],

            //done by dokter
            [['u_status'], 'required', 'on' => 'update_status_instansi', 'message' => '{attribute} harus diisi'],

            //done by umum
            [['u_nik', 'u_nama_depan', 'u_email', 'u_tgl_lahir'], 'required', 'message' => '{attribute} harus diisi', 'on' => 'umum_daftar'],

            [['u_nik', 'u_nama_depan', 'u_email', 'captcha'], 'required', 'on' => 'daftar', 'message' => '{attribute} harus diisi'],
            [['u_email', 'u_nama_depan', 'u_tgl_lahir', 'u_tmpt_lahir', 'u_no_hp', 'u_alamat', 'u_agama', 'u_status_nikah', 'u_kedudukan_keluarga', 'u_alamat', 'u_pendidikan', 'u_nama_ayah', 'u_nama_ibu', 'u_kab', 'u_provinsi', 'u_jabatan_pekerjaan'], 'required', 'on' => 'peserta', 'message' => '{attribute} harus diisi'],
            [['u_email'], 'email', 'message' => 'format {attribute} tidak valid, contoh valid : contoh@gmail.com'],
            [['u_kab', 'u_provinsi', 'u_nama_ayah', 'u_nama_ibu', 'u_nama_pasangan', 'u_dokter', 'u_anggota_darurat_ket', 'u_nama_depan', 'u_nama_belakang', 'u_email', 'u_alamat', 'u_tmpt_lahir', 'u_auth_key', 'u_pekerjaan', 'u_pendidikan', 'u_kedudukan_keluarga', 'u_jabatan_pekerjaan', 'u_tempat_tugas', 'u_password', 'u_alamat_pekerjaan'], 'string', 'max' => 255],
            [['u_no_hp'], 'string', 'max' => 20],
            [['u_no_peserta'], 'string', 'max' => 100],
            [['u_debitur_id'], 'string', 'max' => 4],
            [['u_jadwal_id', 'u_agama', 'u_no_mcu', 'u_istri_ke', 'u_level', 'u_upj_id', 'u_data_pelayanan_id'], 'integer'],
            [['u_nik'], 'unique', 'targetAttribute' => 'u_finish_mcu_at', 'message' => '{attribute} telah tersedia'],
            [['u_jkel', 'u_status', 'u_anggota_darurat', 'u_alamat_dokter', 'u_status_nikah', 'u_rm', 'u_approve_status', 'u_approve_ket', 'u_ktp', 'u_is_pasien_baru'], 'string'],
            // [['u_tgl_lahir'],'date','format'=>'php:d-m-Y','message'=>'format {attribute} tidak valid, contoh valid : 12-02-1989'],
            [['u_tgl_lahir', 'u_last_login', 'u_updated_at', 'u_created_at', 'u_biodata_finish_at', 'u_finish_at', 'u_tgl_terakhir_mcu', 'u_finish_mcu_at', 'u_kuisioner1_finish_at', 'u_kuisioner2_finish_at', 'u_kuisioner3_finish_at'], 'safe'],


            [['ktp'], 'file', 'extensions' => 'jpg,jpeg,png', 'wrongExtension' => '{attribute} harus berupa file {extensions}', 'maxSize' => 204800, 'tooBig' => 'Ukuran file tidak boleh lebih dari 200 KiloByte (KB)', 'skipOnEmpty' => true, 'enableClientValidation' => true],
        ];
    }
    public function attributeLabels()
    {
        return [
            'u_id' => 'U ID',
            'u_rm' => 'No. Rekam Medis',
            'u_nik' => 'NIK',
            'u_no_peserta' => 'No. Peserta',
            'u_nama_depan' => 'Nama Lengkap',
            'u_nama_belakang' => 'Nama Belakang',
            'u_nama_petugas' => 'Nama Petugas Yang Mengisi',
            'u_email' => 'Email',
            'u_alamat' => 'Alamat',
            'u_kab' => 'Kabupaten/Kota',
            'u_provinsi' => 'Provinsi',
            'u_jkel' => 'Jenis Kelamin',
            'u_tgl_lahir' => 'Tanggal Lahir',
            'u_tmpt_lahir' => 'Tempat Lahir',
            'u_no_hp' => 'No. Hp / No. WA',
            'u_status_nikah' => 'Status Pernikahan',
            'u_pekerjaan' => 'Pekerjaan',
            'u_jabatan_pekerjaan' => 'Jabatan Pekerjaan',
            'u_alamat_pekerjaan' => 'Nama Perusahaan Tempat Bekerja',
            'u_pendidikan' => 'Pendidikan Terakhir',
            'u_kedudukan_keluarga' => 'Kedudukan Dalam Keluarga',
            'u_istri_ke' => 'Istri Ke',
            'u_agama' => 'Agama',
            'u_nama_ayah' => 'Nama Ayah',
            'u_nama_ibu' => 'Nama Ibu',
            'u_nama_pasangan' => 'Nama Suami/Istri',
            'u_anggota_darurat' => 'Peserta Termasuk Anggota Tim Penangangan Keadaan Darurat',
            'u_anggota_darurat_ket' => 'Nama Tim Penangangan Keadaan Darurat',
            'u_tgl_terakhir_mcu' => 'Tgl Terakhir MCU',
            'u_dokter' => 'Nama Dokter',
            'u_alamat_dokter' => 'Alamat Dokter',
            // 'u_password' => 'Password',
            'u_status' => 'Status',
            'u_level' => 'Level',
            'u_jadwal_id' => 'Jadwal',
            'u_auth_key' => 'Auth Key',
            'u_last_login' => 'Last Login',
            'u_updated_at' => 'Tanggal Update',
            'u_created_at' => 'Tanggal Simpan',
            'u_debitur_id' => 'Debitur',
            'u_upj_id' => 'Jadwal Pemeriksaan',
            'u_jabatan' => 'Jabatan (Profesi)',
            'u_formasi' => 'Formasi',
            'u_tempat_tugas' => 'Tempat Tugas (rencana penempatan)',
            'u_finish_mcu_at' => 'Tanggal Selesai MCU',
            'u_data_pelayanan_id' => 'ID Data Pelayanan',
            'u_jenis_mcu_id' => 'Jenis Pemeriksaan MCU',
            'u_tgl_periksa' => 'Tanggal Pemeriksaan',
            'u_paket_id' => 'Jenis Paket',

            'u_approve_status' => 'Status Verifikasi',
            'u_approve_ket' => 'Keterangan Verifikasi',
            'u_is_pasien_baru' => 'Pasien Baru Atau Lama ?',
            'u_ktp' => 'KTP',
            'ktp' => 'KTP'
        ];
    }
    function beforeSave($model)
    {
        if ($this->u_tgl_lahir != NULL) {
            $this->u_tgl_lahir = date('Y-m-d', strtotime($this->u_tgl_lahir));
        }
        if (!empty($this->u_tgl_terakhir_mcu)) {
            $this->u_tgl_terakhir_mcu = date('Y-m-d', strtotime($this->u_tgl_terakhir_mcu));
        }
        if ($this->u_pekerjaan != NULL) {
            $pkr = Pekerjaan::find()->where(['Nomor' => $this->u_pekerjaan])->asArray()->limit(1)->one();
            if ($pkr != NULL) {
                $this->u_pekerjaan_nama = $pkr['PerkerjaanJabatan'];
            }
        }
        if ($this->u_kedudukan_keluarga != 'istri') {
            $this->u_istri_ke = NULL;
        }
        if ($this->scenario == 'create_akun_instansi') {
            $this->u_level = 3;
            $this->u_created_at = date('Y-m-d H:i:s');
        }
        if ($this->scenario == 'create_peserta_by_instansi' || $this->scenario == "insert_by_excel") {
            $this->u_level = 2;
            $this->u_instansi_id = Yii::$app->user->identity->u_id;
        }

        //daftar oleh peserta umum
        if ($this->scenario == "peserta_daftar") {
            $this->u_tgl_periksa = date('Y-m-d', strtotime($this->u_tgl_periksa)) . ' ' . date('H:i:s');
            $this->u_biodata_finish_at = date('Y-m-d H:i:s');
            if ($this->is_riwayat_mcu == 'n') {
                $this->u_tgl_terakhir_mcu = NULL;
                $this->u_dokter = NULL;
                $this->u_alamat_dokter = NULL;
            }
            $this->u_nama_pasangan = $this->u_nama_pasangan != NULL ? $this->u_nama_pasangan : NULL;
            $this->u_anggota_darurat = $this->u_anggota_darurat;
            $this->u_anggota_darurat_ket = $this->u_anggota_darurat == 1 ? $this->u_anggota_darurat_ket : NULL;
            $this->u_debitur_id = 9999;
            $this->u_created_at = date('Y-m-d H:i:s');
        }

        //verifikasi rm untuk peserta instansi
        if ($this->scenario == 'verify_by_rm') {
            if ($this->u_approve_status == '2') {
                $this->u_approve_ket = NULL;
                $this->u_approve_by = Yii::$app->user->identity->u_id;
                $this->u_approve_at = date('Y-m-d H:i:s');
            }
        }

        return parent::beforeSave($model);
    }
    function validateTanggalPeriksa($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->u_tgl_periksa == date('d-m-Y')) {
                $setting = Yii::$app->db->createCommand("select set_value from " . Setting::tableName() . " where set_kode='batas_jam_daftar'")->queryOne();
                $now = date('H:i');
                if (strtotime($now) > strtotime($setting['set_value'])) {
                    $this->addError($attribute, 'Anda sudah melewati batas jam pendaftaran untuk hari ini : ' . $setting['set_value'] . '. Silahkan pilih tanggal pemeriksaan untuk besok hari');
                }
            }
        }
    }
    function isAdm()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->u_level == '1' || Yii::$app->user->identity->u_level == '3') {
                return true;
            }
        }
        return false;
    }
    function isPeserta()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->u_level == '2') {
                return true;
            }
        }
        return false;
    }
    public static function findIdentity($id)
    {
        $user = UserDaftar::find()->where(['ud_id' => $id])->limit(1)->one(); //user :  peserta/pasien
        if ($user == NULL) {
            $user = self::findOne(['u_id' => $id, 'u_status' => '1']); //user : instansi,dokter
            if ($user == NULL) {
                $user = Pasien::find()->where(['NO_PASIEN' => sprintf('%08d', $id)])->limit(1)->one(); //user : pasien yang sudah di acc
            }
        }
        return $user;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    //find peserta instansi
    static function findPeserta($nik, $tgl_lahir)
    {
        $query = self::find()->where(['u_nik' => trim($nik), 'u_tgl_lahir' => date('Y-m-d', strtotime($tgl_lahir))]);
        if (Yii::$app->params['validate_pi']) {
            $query->andWhere(['u_approve_status' => '2']);
        }
        return $query->limit(1)->one();
    }
    public static function findByUsername($nik)
    {
        return static::find()->where(['u_nik' => trim($nik), 'u_status' => '1'])->andWhere('u_finish_mcu_at is null')->limit(1)->one(); //findOne(['u_nik' => trim($nik), 'u_status' =>'1']);
    }
    static function findAdminByUsername($nik)
    {
        return static::find()->where(['u_nik' => trim($nik), 'u_status' => '1'])->andWhere(['or', ['u_level' => 4], ['u_level' => 5]])->limit(1)->one();
    }
    static function findInstansi($nik)
    {
        return self::find()->where(['u_nik' => trim($nik), 'u_level' => 3])->limit(1)->one();
    }
    public function getId()
    {
        return $this->u_id;
    }
    public function getAuthKey()
    {
        return $this->u_auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->u_auth_key === $authKey;
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->u_password);
    }
    function getPekerjaan()
    {
        return $this->hasOne(Pekerjaan::className(), ['Nomor' => 'u_pekerjaan']);
    }
    function getAgama()
    {
        return $this->hasOne(Agama::className(), ['Kode' => 'u_agama']);
    }
    function getUserberkas()
    {
        return $this->hasOne(UserBerkas::className(), ['ub_user_id' => 'u_id']);
    }
    function getJadwal()
    {
        return $this->hasOne(Jadwal::className(), ['j_id' => 'u_jadwal_id']);
    }
    function getJadwalasli()
    {
        return $this->hasOne(Jadwal::className(), ['j_id' => 'u_jadwal_asli_id']);
    }
    function getKuisionerbiodata()
    {
        return $this->hasOne(UserKusionerBiodata::className(), ['ukb_user_id' => 'u_id']);
    }
    function getUserjadwal()
    {
        return $this->hasMany(UserJadwal::className(), ['uj_user_id' => 'u_id'])->where(['uj_status' => '1']);
    }
    function getJadwalperiksa()
    {
        return $this->hasOne(UserPermintaanJadwal::className(), ['upj_id' => 'u_upj_id']);
    }
    function getJenismcu()
    {
        return $this->hasOne(JenisMcu::className(), ['jm_id' => 'u_jenis_mcu_id']);
    }
    function getInstansi()
    {
        return $this->hasOne(User::className(), ['u_id' => 'u_instansi_id'])->where("u_level = 3");
    }
    function getPaket()
    {
        return $this->hasOne(Paket::className(), ['kode' => 'u_paket_id']);
    }
    function setDataUser()
    {
        $u = Yii::$app->user->identity;
        $this->u_rm = $u->ud_rm;
        $this->u_no_mcu = $u->ud_rm;
        $this->u_nama_depan = $u->ud_nama;
        $this->u_jkel = $u->ud_jkel;
        $this->u_email = $u->ud_email;
        $this->u_alamat = $u->ud_alamat;
        $this->u_kab = $u->ud_kabupaten;
        $this->u_provinsi = $u->ud_provinsi;
        $this->u_tgl_lahir = $u->ud_tgl_lahir;
        $this->u_tmpt_lahir = $u->ud_tmpt_lahir;
        $this->u_no_hp = $u->ud_telp;
        $this->u_status = '1';
        $this->u_level = 2;
        $this->u_agama = $u->ud_agama;
        $this->u_kedudukan_keluarga = $u->ud_kedudukan_keluarga;
        $this->u_status_nikah = $u->ud_status_nikah;
        $this->u_pekerjaan = $u->ud_pekerjaan;
        $this->u_jabatan_pekerjaan = $u->ud_jabatan_pekerjaan;
        $this->u_alamat_pekerjaan = $u->ud_alamat_pekerjaan;
        $this->u_tempat_tugas = $u->ud_tempat_tugas;
        $this->u_pendidikan = $u->ud_pendidikan;
        $this->u_nama_ayah = $u->ud_nama_ayah;
        $this->u_nama_ibu = $u->ud_nama_ibu;
        $this->u_nama_pasangan = $u->ud_nama_pasangan;
        $this->u_anggota_darurat = $u->ud_anggota_darurat;
        $this->u_anggota_darurat_ket = $u->ud_anggota_darurat_ket;
        $this->u_approve_status = $u->ud_approve_status;
        $this->u_approve_ket = $u->ud_approve_ket;
        $this->u_is_pasien_baru = $u->ud_is_pasien_baru;
    }
    function saveBiodata()
    {
        if ($this->validate()) {
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $this->save(false);
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            $this->error_msg = $this->errors;
            return false;
        }
    }
    function deleteFile($file)
    {
        if (!empty($file)) {
            if (file_exists(Yii::$app->params['storage'] . $file) && is_file(Yii::$app->params['storage'] . $file)) {
                unlink(Yii::$app->params['storage'] . $file);
            }
        }
    }
    function finishReg()
    {
        $this->u_finish_at = date('Y-m-d H:i:s');
        $transaction = self::getDb()->beginTransaction();
        $trans_pasien = Pasien::getDb()->beginTransaction();
        $trans_pelayanan = PelayananMcu::getDb()->beginTransaction();
        try {

            if (empty($this->u_rm)) {
                $checkPasien = Pasien::findOne(['NOIDENTITAS' => $this->u_nik]);
                $pasien = $checkPasien == NULL ? new Pasien() : $checkPasien;
                //simpan ke pasien simrs 
                try {
                    if (!$pasien->savePasien($this)) {
                        $trans_pasien->rollBack();
                        return false;
                    }
                    $this->u_rm = $pasien->NO_PASIEN;
                } catch (\Exception $e) {
                    $trans_pasien->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $trans_pasien->rollBack();
                    throw $e;
                }
            }

            //simpan di app mcu
            $checkPelayanan = PelayananMcu::find()->where(['no_ujian' => $this->u_nik])->andWhere('no_registrasi is null')->orderBy(['tanggal_pemeriksaan' => SORT_DESC])->limit(1)->one();
            $mcu = $checkPelayanan == NULL ? new PelayananMcu() : $checkPelayanan;
            try {
                if (!$mcu->savePelayanan($this)) {
                    $trans_pelayanan->rollBack();
                    $trans_pasien->rollBack();
                    return false;
                }
                $this->u_data_pelayanan_id = $mcu->id_data_pelayanan;
            } catch (\Exception $e) {
                $trans_pelayanan->rollBack();
                $trans_pasien->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $trans_pelayanan->rollBack();
                $trans_pasien->rollBack();
                throw $e;
            }

            $this->save(false);
            $transaction->commit();
            $trans_pasien->commit();
            $trans_pelayanan->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $trans_pasien->rollBack();
            $trans_pelayanan->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $trans_pasien->rollBack();
            $trans_pelayanan->rollBack();
            throw $e;
        }
        return false;
    }
    function finishRegUmum()
    {
        $this->u_finish_at = date('Y-m-d H:i:s');
        $transaction = self::getDb()->beginTransaction();
        $trans_pelayanan = PelayananMcu::getDb()->beginTransaction();
        $trans_pasien = Pasien::getDb()->beginTransaction();
        $trans_daftar = UserDaftar::getDb()->beginTransaction();
        try {
            //simpan pasien jika pasien baru
            if ($this->u_is_pasien_baru == 'y') {
                if ($this->u_rm == NULL) {
                    $pasien = new Pasien();
                    try {
                        if (!$pasien->savePesertaAsPasien()) {
                            $trans_pasien->rollBack();
                            return false;
                        }
                        $this->u_rm = $pasien->NO_PASIEN;
                    } catch (\Exception $e) {
                        $trans_pasien->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $trans_pasien->rollBack();
                        throw $e;
                    }

                    $ud = UserDaftar::findOne(Yii::$app->user->identity->ud_id);
                    try {
                        $ud->ud_rm = $this->u_rm;
                        $ud->save(false);
                    } catch (\Exception $e) {
                        $trans_daftar->rollBack();
                        $trans_pasien->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $trans_daftar->rollBack();
                        $trans_pasien->rollBack();
                        throw $e;
                    }
                }
            }

            //simpan di app mcu
            $checkPelayanan = PelayananMcu::find()->where(['no_ujian' => $this->u_nik])->andWhere('no_registrasi is null')->orderBy(['tanggal_pemeriksaan' => SORT_DESC])->limit(1)->one();
            $mcu = $checkPelayanan == NULL ? new PelayananMcu() : $checkPelayanan;
            try {
                if (!$mcu->savePelayananUmum($this)) {
                    $trans_pelayanan->rollBack();
                    $trans_daftar->rollBack();
                    $trans_pasien->rollBack();
                    return false;
                }
                $this->u_data_pelayanan_id = $mcu->id_data_pelayanan;
            } catch (\Exception $e) {
                $trans_pelayanan->rollBack();
                $trans_daftar->rollBack();
                $trans_pasien->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $trans_pelayanan->rollBack();
                $trans_daftar->rollBack();
                $trans_pasien->rollBack();
                throw $e;
            }

            $this->save(false);
            $transaction->commit();
            $trans_pelayanan->commit();
            $trans_pasien->commit();
            $trans_daftar->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $trans_pelayanan->rollBack();
            $trans_pasien->rollBack();
            $trans_daftar->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $trans_pelayanan->rollBack();
            $trans_pasien->rollBack();
            $trans_daftar->rollBack();
            throw $e;
        }
        return false;
    }
    function setNoPasien()
    {
        $max = Pasien::find()->max('NO_PASIEN');
        return sprintf('%08d', $max + 1);
    }
    function setJadwal()
    {
        $count = self::find()->where('u_finish_at IS NOT NULL')->count();
        $jadwal = Jadwal::find()->select('j_id')->where('j_kuota_min <= :count AND j_kuota_max >= :count', [':count' => $count])->asArray()->limit(1)->one();
    }
    function importData($up, $data)
    {
        unset($data[1]);
        if (count($data) > 0) {
            $trans = self::getDb()->beginTransaction();
            try {
                foreach ($data as $d) {
                    $check = User::find()->where('u_nik = :nik and u_instansi_id = :instansi and u_finish_mcu_at is null', [':nik' => $d['B'], ':instansi' => Yii::$app->user->identity->u_id])->count();
                    if ($check < 1) {
                        $jadwal = UserPermintaanJadwal::find()->with(['permintaan'])->where(['upj_id' => trim($d['J'])])->asArray()->limit(1)->one();
                        if ($jadwal != NULL) {
                            $count = User::find()->where(['u_upj_id' => trim($d['J'])])->count();
                            if ($count < $jadwal['upj_kuota']) {
                                $paket = UserPermintaanPaket::find()->where(['upp_up_id' => $jadwal['upj_up_id'], 'upp_paket_id' => trim($d['K'])])->count();
                                if ($paket > 0) {
                                    $user_permintaan = UserPermintaan::find()->where(['up_id' => $jadwal['upj_up_id']])->asArray()->limit(1)->one();
                                    $m = new User();
                                    $m->scenario = "insert_by_excel";
                                    $m->u_nik = str_replace(' ', '', trim($d['B']));
                                    $m->u_no_peserta = str_replace(' ', '', trim($d['C']));
                                    $m->u_nama_depan = trim($d['D']);
                                    $m->u_tmpt_lahir = trim($d['E']);
                                    $m->u_tgl_lahir = !empty(strtotime($d['F'])) ? date('Y-m-d', strtotime($d['F'])) : date('Y-m-d');
                                    $m->u_pekerjaan = trim($d['G']);
                                    $m->u_jabatan = trim($d['H']);
                                    $m->u_tempat_tugas = trim($d['I']);
                                    $m->u_upj_id = trim($d['J']);
                                    $m->u_paket_id = trim($d['K']);
                                    $m->u_jenis_mcu_id = $user_permintaan != NULL ? $user_permintaan['up_jenis_mcu_id'] : NULL;
                                    $m->u_debitur_id = $jadwal['permintaan']['up_debitur_id'];
                                    if ($m->validate()) {
                                        if (!$m->save(false)) {
                                            $this->error_msg = 'Import excel gagal dilakukan, silahkan periksa kembali validitas data';
                                            $trans->rollBack();
                                            return false;
                                        }
                                    } else {
                                        $this->error_msg = $m->errors;
                                        $trans->rollBack();
                                        return false;
                                    }
                                } else {
                                    $this->error_msg = "Kode Paket Tidak Valid. Silahkan periksa kembali KODE PAKET";
                                    $trans->rollBack();
                                    return false;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            $this->error_msg = "Kode Tanggal Pemeriksaan Tidak Valid. Silahkan periksa kembali KODE TANGGAL PENERIKSAAN";
                            $trans->rollBack();
                            return false;
                        }
                    } else {
                        $this->error_msg = "Peserta dengan NIK <b>" . $d['B'] . "</b> sudah tersedia, silahkan periksa kembali file excel anda";
                        $trans->rollBack();
                        return false;
                    }
                }
                $trans->commit();
                return true;
            } catch (\Exception $e) {
                $trans->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $trans->rollBack();
                throw $e;
            }
        } else {
            $this->error_msg = "Data tidak ditemukan di file excel";
            return false;
        }
    }
    static function getLatest($nik)
    {
        $data = self::find()->where(['u_nik' => $nik])->andWhere("u_tgl_periksa >= '" . date('Y-m-d') . "' and u_finish_mcu_at is null")
            ->with([
                'jadwal', 'kuisionerbiodata',
                'jadwalperiksa' => function ($q) {
                    $q->with(['permintaan' => function ($q) {
                        $q->with(['user']);
                    }]);
                }
            ])->orderBy(['u_created_at' => SORT_DESC])->limit(1)->one();
        if ($data != NULL) {
            $setting = Yii::$app->db->createCommand("select set_value from " . Setting::tableName() . " where set_kode='batas_jam_daftar'")->queryOne();
            if (date('d-m-Y', strtotime($data['u_tgl_periksa'])) == date('d-m-Y')) { //jika hari ini
                return strtotime(date('H:i', strtotime($data['u_tgl_periksa']))) <= strtotime($setting['set_value']) ? $data : NULL;
            } else { //jika besok
                return $data;
            }
        }
        return NULL;
    }
    function saveVerifikasiRm()
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if ($this->u_approve_status == '2' && $this->u_rm != NULL && $this->u_data_pelayanan_id != NULL) {
                PelayananMcu::updateAll(['no_rekam_medik' => $this->u_rm], ['id_data_pelayanan' => $this->u_data_pelayanan_id]);
            }
            $this->save(false);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getUserBrother()
    {
        return $this->hasMany(UserBrother::className(), ['nik' => 'u_nik']);
    }
}
