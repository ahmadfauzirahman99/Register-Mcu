<?php
namespace app\widgets;
use app\models\User;
use app\models\Kuisioner;
use app\models\UserKuisioner;
use yii\jui\DatePicker;
use Yii;
class App_
{
    static function isRegClose()
    {
        if(strtotime(date('d-m-Y H:i:s'))>strtotime(Yii::$app->params['reg_close_date'])){
            Yii::$app->session->setFlash('error','Pendaftaran online sudah ditutup');
            return true;
        }
        return false;
    }
    static function isFinish()
    {
        if(!Yii::$app->user->isGuest){
            $user=User::find()->where(['u_id'=>Yii::$app->user->identity->u_id])->andWhere('u_finish_at IS NOT NULL')->asArray()->limit(1)->one();
            if($user!=NULL){
                return true;
            }
        }
        return false;
    }
    static function isPeserta()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->isPeserta()){
                return true;
            }
        }
        return false;
    }
    static function isAdm()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->isAdm()){
                return true;
            }
        }
        return false;
    }
    static function isMakalahOpen()
    {
        if(strtotime(date('d-m-Y H:i:s'))>=strtotime(Yii::$app->params['makalah_date_open']) && strtotime(date('d-m-Y H:i:s'))<=strtotime(Yii::$app->params['makalah_date_close'])){
            return true;
        }
        return false;
    }
    static function riwayatSosial($data,$tab=5,$c=false)
    {
        $h="";
        $no=1;
        foreach($data as $dt){
            $child = Kuisioner::find()->where(['k_id_parent'=>$dt['k_id']])->asArray()->all();
            $value=UserKuisioner::find()->select('uk_ceklis,uk_keterangan')->where(['u_id'=>Yii::$app->user->identity->u_id,'k_id'=>$dt['k_id']])->asArray()->limit(1)->one();
            $h.="<tr>
                    <td>".($tab==5 ? $no : '')."</td>
                    <td style='padding-left:".$tab."px; ".( count($child)>0 && $c ? "font-weight:bolder;" : "" )."'>".$dt['k_isi_indo']."<br><small><i>".$dt['k_isi_eng']."</i></small></td>
                    <td>".( $dt['k_tipe']==0 ? 
                        "<label><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='1' ".( $value!=NULL ? ( $value['uk_ceklis']==1 ? "checked" : "" ) : "" )."> Iya</label>
                        <label style='color:red;'><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='0' ".( $value!=NULL ? ( $value['uk_ceklis']==0 ? "checked" : "" ) : "checked" )."> Tidak</label>
                        " : '' )."
                    </td>
                    <td>".( $dt['k_tipe']==0 ?
                        "<input type='text' class='form-control' name='riwayat-jelaskan[".$dt['k_id']."]' value='".( $value!=NULL ? $value['uk_keterangan'] : NULL )."'>
                        " : '')."
                    </td>
                </tr>";
            if(count($child)>0){
                $h.=self::riwayatSosial($child,$tab+=20,true);
                $tab-=20;
            }
            $no++;
        }
        return $h;
    }
    static function pdfRiwayatSosial($data,$tab=5,$c=false)
    {
        $h="";
        $no=1;
        foreach($data as $dt){
            $child = Kuisioner::find()->where(['k_id_parent'=>$dt['k_id']])->asArray()->all();
            $value=UserKuisioner::find()->select('uk_ceklis,uk_keterangan')->where(['u_id'=>Yii::$app->user->identity->u_id,'k_id'=>$dt['k_id']])->asArray()->limit(1)->one();
            $h.="<tr>
                    <td>".($tab==5 ? $no : '')."</td>
                    <td style='padding-left:".$tab."px; ".( count($child)>0 && $c ? "font-weight:bolder;" : "" )."'>".$dt['k_isi_indo']."<br><small><i>".$dt['k_isi_eng']."</i></small></td>
                    <td>".( $dt['k_tipe']==0 ? 
                        "<label><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='1' ".( $value!=NULL ? ( $value['uk_ceklis']==1 ? "checked='checked'" : "" ) : "" )."> Iya</label><br>
                        <label style='color:red;'><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='0' ".( $value!=NULL ? ( $value['uk_ceklis']==0 ? "checked='checked'" : "" ) : "checked='checked'" )."> Tidak</label>
                        " : '' )."
                    </td>
                    <td>".( $dt['k_tipe']==0 ? ( $value!=NULL ? $value['uk_keterangan'] : NULL ) : '')."</td>
                </tr>";
            if(count($child)>0){
                $h.=self::pdfRiwayatSosial($child,$tab+=20,true);
                $tab-=20;
            }
            $no++;
        }
        return $h;
    }
    static function riwayatPenyakit($data,$tab=5,$c=false)
    {
        $h="";
        $no=1;
        foreach($data as $dt){
            $child = Kuisioner::find()->where(['k_id_parent'=>$dt['k_id']])->asArray()->all();
            $value=UserKuisioner::find()->select('uk_ceklis,uk_keterangan')->where(['u_id'=>Yii::$app->user->identity->u_id,'k_id'=>$dt['k_id']])->asArray()->limit(1)->one();
            if($dt['k_id']==9){
                $h.="
                    <tr>
                        <td colspan='4'>
                            ".$dt['k_isi_indo']."<br><small><i>".$dt['k_isi_eng']."</i></small>
                            <textarea class='form-control' name='riwayat-jelaskan[".$dt['k_id']."]'>".( $value!=NULL ? $value['uk_keterangan'] : NULL )."</textarea>
                        </td>
                    </tr>
                ";
            }elseif(($dt['k_id']>=46 && $dt['k_id']<=53) || $dt['k_id']==57 ){
                $date=DatePicker::widget([
                    'name'  => "riwayat-jelaskan[".$dt['k_id']."]",
                    'value'  => $value!=NULL ? $value['uk_keterangan'] : NULL,
                    'dateFormat' => 'dd-MM-yyyy',
                    'options'=>['class'=>'form-control','autocomplete'=>'off','placeholder'=>'Tanggal Pemberian Vaksin'],
                    'clientOptions'=>[
                        'maxDate'=>date('d-m-Y'),
                        'changeMonth'=>true,
                        'changeYear'=>true,
                    ]
                ]);
                $h.="
                    <tr>
                        <td>".$no."</td>
                        <td>".$dt['k_isi_indo']."</td>
                        <td colspan='2'>
                            ".$date."
                        </td>
                    </tr>
                ";
            }else{
                $h.="<tr>
                        <td>".($tab==5 ? $no : '')."</td>
                        <td style='padding-left:".$tab."px; ".( count($child)>0 && $c ? "font-weight:bolder;" : "" )."'>".$dt['k_isi_indo']."<br><small><i>".$dt['k_isi_eng']."</i></small></td>
                        <td>".( $dt['k_tipe']==0 ? 
                            "<label><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='1' ".( $value!=NULL ? ( $value['uk_ceklis']==1 ? "checked" : "" ) : "" )."> Iya</label>
                            <label style='color:red;'><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='0' ".( $value!=NULL ? ( $value['uk_ceklis']==0 ? "checked" : "" ) : "checked" )."> Tidak</label>
                            " : '' )."
                        </td>
                        <td>".( $dt['k_tipe']==0 ?
                            "<input type='text' class='form-control' name='riwayat-jelaskan[".$dt['k_id']."]' value='".( $value!=NULL ? $value['uk_keterangan'] : NULL )."'>
                            " : '')."
                        </td>
                    </tr>";
            }
            if(count($child)>0){
                $h.=self::riwayatPenyakit($child,$tab+=20,true);
                $tab-=20;
            }
            $no++;
        }
        return $h;
    }
    static function pdfRiwayatPenyakit($data,$tab=5,$c=false)
    {
        $h="";
        $no=1;
        foreach($data as $dt){
            $child = Kuisioner::find()->where(['k_id_parent'=>$dt['k_id']])->asArray()->all();
            $value=UserKuisioner::find()->select('uk_ceklis,uk_keterangan')->where(['u_id'=>Yii::$app->user->identity->u_id,'k_id'=>$dt['k_id']])->asArray()->limit(1)->one();
            if($dt['k_id']==9){
                $h.="
                    <tr>
                        <td colspan='4'>
                            ".$dt['k_isi_indo']."ccc<br><small><i>".$dt['k_isi_eng']."</i></small><br>
                            ".( $value!=NULL ? $value['uk_keterangan'] : NULL )."
                        </td>
                    </tr>
                ";
            }elseif(($dt['k_id']>=46 && $dt['k_id']<=53) || $dt['k_id']==57 ){
                $h.="
                    <tr>
                        <td>".$no."</td>
                        <td>".$dt['k_isi_indo']."</td>
                        <td colspan='2' align='center'>
                            ".( $value!=NULL ? $value['uk_keterangan'] : NULL )."
                        </td>
                    </tr>
                ";
            }else{
                $h.="<tr>
                        <td>".($tab==5 ? $no : '')."</td>
                        <td style='padding-left:".$tab."px; ".( count($child)>0 && $c ? "font-weight:bolder;" : "" )."'>".$dt['k_isi_indo']."<br><small><i>".$dt['k_isi_eng']."</i></small></td>
                        <td>".( $dt['k_tipe']==0 ? 
                            "<label><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='1' ".( $value!=NULL ? ( $value['uk_ceklis']==1 ? "checked='checked'" : "" ) : "" )."> Iya</label><br>
                            <label style='color:red;'><input type='radio' name='riwayat-pilihan[".$dt['k_id']."]' value='0' ".( $value!=NULL ? ( $value['uk_ceklis']==0 ? "checked='checked'" : "" ) : "checked='checked'" )."> Tidak</label>
                            " : '' )."
                        </td>
                        <td>".( $dt['k_tipe']==0 ? ( $value!=NULL ? $value['uk_keterangan'] : NULL ) : '')."</td>
                    </tr>";
            }
            if(count($child)>0){
                $h.=self::pdfRiwayatPenyakit($child,$tab+=20,true);
                $tab-=20;
            }
            $no++;
        }
        return $h;
    }
}