<?php
namespace app\models;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class Pasien extends ActiveRecord implements IdentityInterface
{
    public $AUTH_KEY;
    public static function tableName()
    {
        return 'PASIEN';
    }
    public static function getDb()
    {
        return Yii::$app->get('dbsimrs');
    }
    public static function primaryKey()
    {
        return ["NO_PASIEN"];
    }
    public function rules()
    {
        return [
            [['NO_PASIEN','NAMA']],
            [['NOIDENTITAS','NAMA','TP_LAHIR','TGL_LAHIR','JENIS_KEL','KD_AGAMA','PROPINSI','KECAMATAN','KABUPATEN'],'required','message'=>''],

            [['STATUSWN'], 'exist' ,'targetClass' => 'app\models\Kewarganegaraan', 'targetAttribute' => 'Kode', 'message'=>\Yii::t('app','Kewarganegaraan Tidak Ditemukan')],
            [['KD_AGAMA'], 'exist' ,'targetClass' => 'app\models\Agama', 'targetAttribute' => 'Kode', 'message'=>\Yii::t('app','Agama Tidak Ditemukan')],
            [['GOLDAR'], 'exist' ,'targetClass' => 'app\models\GolonganDarah', 'targetAttribute' => 'Kode', 'message'=>\Yii::t('app','Golongan Darah Tidak Ditemukan')],

            ['TGL_LAHIR','date','message'=>'Format {attribute} tidak sesuai', 'format' => 'php:Y-m-d'],
            ['NO_PASIEN','string','max'=>8],
            ['NAMA','string','max'=>50],
            ['MARGA','string','max'=>50],
            ['TP_LAHIR','string','max'=>30],
            ['JENIS_KEL','in','range'=>['L','P']],
            ['STATUS','string','max'=>2],
            ['PEKERJAAN','string','max'=>40],
            ['PENDAKH','string','max'=>40],
            ['NO_HP','string','max'=>12],
            ['NO_TELP','string','max'=>30],
            ['NAMAAYAH','string','max'=>30],
            ['NAMAIBU','string','max'=>30],
            ['NAMAPASANGAN','string','max'=>30],
            ['ALAMAT','string','max'=>60],
            ['RT','number','max'=>999, 'min'=>1,'message'=>'{attribute} Harus Angka'],
            ['RW','number','max'=>999, 'min'=>1,'message'=>'{attribute} Harus Angka'],
            ['PROPINSI','string','max'=>30],
            ['KABUPATEN','string','max'=>30],
            ['KECAMATAN','string','max'=>30],
            ['KD_POS','number','max'=>99999, 'min'=>1,'message'=>'{attribute} Harus Angka'],

//            [['NAMAPANGGILAN','DESA','RW','KELURAHAN','NO_TELP','NO_DEBT','NO_KARTU','ATASNAMA','HUBUNGAN','AGAMA','NO_DAFTAR','KARTU','PENJWB','NAMAPEN','ALMPEN1','ALMPEN2','TELPPEN','HPPEN','PHOTO','HUBPEN','RTPEN','RWPEN','KODEPOSPEN','DESAPEN','KECPEN','KABPEN','NOIDENTITAS','PROPINSI','PROPPEN','MODIFY_ID','STATUSWN','ALMKANTOR','KOTAKANTOR','PROPKANTOR','TELPKANTOR','GOLDAR','NAMAKANTOR','NUMURTH','HOBI','KEBIASAAN','LAIN','NO_PASIENIBU','KODEPROP','KODEKAB','KODEKEC','KODEKEL','MARGA','PEKERJAANPEN','PENDAKHPEN'],'default','value'=>''],
            [['MODIFY_DATE'],'default','value'=>NULL],
            [['NAKTIF'],'default','value'=>false],

            [['JENIS_KEL'],'default','value'=>'L'],
            [['PROPINSI'],'default','value'=>'Riau'],


            //DATA PENANGGUNG
            ['NAMAPEN','string','max'=>30],
            ['HUBPEN','string','max'=>20],
            ['ALMPEN1','string','max'=>40],
            ['RTPEN','number','max'=>999, 'min'=>1,'message'=>'{attribute} Harus Angka'],
            ['RWPEN','number','max'=>999, 'min'=>1,'message'=>'{attribute} Harus Angka'],
            ['KODEPOSPEN','number','max'=>99999, 'min'=>1,'message'=>'{attribute} Harus Angka'],
            ['DESAPEN','string','max'=>30],
            ['KECPEN','string','max'=>30],
            ['KABPEN','string','max'=>30],
            ['PROPPEN','string','max'=>30],
            ['NOIDENTITAS','string','max'=>30],
            ['TELPPEN','string','max'=>12],
            ['HPPEN','string','max'=>12],
            ['HOBI','string','max'=>50],
            ['KEBIASAAN','string','max'=>50],
            ['LAIN','string','max'=>50],
        ];
    }
    public function attributeLabels()
    {
        return [
            'NO_PASIEN' => 'No. RM',
            'NAMA' => 'Nama Pasien',
            'ALAMAT' => 'Alamat',
            'NOIDENTITAS' => 'ID/NIK',
            'TP_LAHIR'=>'Tempat Lahir',
            'TGL_LAHIR'=>'Tanggal Lahir',
            'KD_AGAMA'=>'Agama',
            'JENIS_KEL'=>'Jenis Kelamin',
            'NAMAAYAH'=>'Nama Ayah',
            'NAMAIBU'=>'Nama Ibu',
        ];
    }
    function getRawatinap()
    {
        return $this->hasMany(VWRAWATINAP::className(),['NO_PASIEN'=>"NO_PASIEN"]);
    }
    function getRawatpoli()
    {
        return $this->hasMany(VWRAWATPOLI::className(),['NO_PASIEN'=>"NO_PASIEN"]);
    }
    function setNoPasien()
    {
        $max = self::find()->max('NO_PASIEN');
        $t=false;
        while(!$t){
            $max=sprintf('%08d',$max+1);
            $check=Pasien::find()->where(['NO_PASIEN'=>$max])->count();
            if($check<1){
                $t=true;
            }
        }
        return $max;
    }
    function savePasien($obj)
    {
		$this->NO_PASIEN=$this->setNoPasien();
		$this->NOIDENTITAS=$obj->u_nik;
		$this->NAMA=$obj->u_nama_depan.' '.$obj->u_nama_belakang;
		$this->TP_LAHIR=$obj->u_tmpt_lahir;
		$this->TGL_LAHIR=date('Y-m-d H:i:s',strtotime($obj->u_tgl_lahir));
		$this->JENIS_KEL=$obj->u_jkel;
		$this->STATUS=$obj->u_status_nikah;
		$this->KD_AGAMA=$obj->u_agama;
		$this->PEKERJAAN=$obj->u_pekerjaan_nama;
		$this->PENDAKH=$obj->u_pendidikan;
		$this->NO_TELP=$obj->u_no_hp;
		$this->NO_HP=$obj->u_no_hp;
		$this->NAMAAYAH=$obj->u_nama_ayah;
		$this->NAMAIBU=$obj->u_nama_ibu;
		$this->NAMAPASANGAN=$obj->u_nama_pasangan;
		$this->ALAMAT=$obj->u_alamat;
		$this->KABUPATEN=$obj->u_kab;
		$this->PROPINSI=$obj->u_provinsi;
		$this->NO_DEBT=$obj->u_debitur_id;
		if($obj->jadwalperiksa!=NULL){
			$this->TGL_DAFTAR=$obj->jadwalperiksa->upj_tgl;
		}
		$this->CREATE_ID=$obj->u_nik;
		$this->CREATE_DATE=date('Y-m-d H:i:s');
		if($this->save(false)){
			return true;
		}else{
			return false;
		}
    }
    function savePesertaAsPasien()
    {
        $obj=Yii::$app->user->identity;
        if(empty($obj->ud_rm)){
            $this->NO_PASIEN=$this->setNoPasien();
        }
		$this->NOIDENTITAS=$obj->ud_nik;
		$this->NAMA=$obj->ud_nama;
		$this->TP_LAHIR=$obj->ud_tmpt_lahir;
		$this->TGL_LAHIR=date('Y-m-d H:i:s',strtotime($obj->ud_tgl_lahir));
		$this->JENIS_KEL=$obj->ud_jkel;
		$this->STATUS=$obj->ud_status_nikah;
		$this->KD_AGAMA=$obj->ud_agama;
		$this->PEKERJAAN=$obj->pekerjaan!=NULL ? $obj->pekerjaan->PerkerjaanJabatan : NULL;
		$this->PENDAKH=(new UserDaftar())->pendidikan[$obj->ud_pendidikan];
		$this->NO_TELP=$obj->ud_telp;
		$this->NO_HP=$obj->ud_telp;
		$this->NAMAAYAH=$obj->ud_nama_ayah;
		$this->NAMAIBU=$obj->ud_nama_ibu;
		$this->NAMAPASANGAN=$obj->ud_nama_pasangan;
		$this->ALAMAT=$obj->ud_alamat;
		$this->KABUPATEN=$obj->ud_kabupaten;
		$this->PROPINSI=$obj->ud_provinsi;
		$this->NO_DEBT=9999;
		$this->TGL_DAFTAR=date('Y-m-d H:i:s');
		$this->CREATE_ID="regmcu";
        $this->CREATE_DATE=date('Y-m-d H:i:s');
		if($this->save(false)){
			return true;
		}else{
			return false;
		}
    }
    public function getId()
    {
        return $this->NO_PASIEN;
    }
    public function getAuthKey()
    {
        return $this->AUTH_KEY;
    }
    public function validateAuthKey($authKey)
    {
        return $this->AUTH_KEY === $authKey;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    public static function findIdentity($id)
    {
        return static::findOne(['NO_PASIEN'=>$id])->limit(1)->one();
    }
}