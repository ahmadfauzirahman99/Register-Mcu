<?php
use app\models\Kuisioner;
use app\models\UserKuisioner;
use yii\helpers\Url;
use yii\widgets\DetailView;
$this->title="Detail Peserta";
?>
<h4><?php echo $this->title; ?></h4>
<p>
    <a href="<?php echo Url::to(['peserta-detail','id'=>$user]) ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
</p>
<strong>BIODATA</strong><hr>
<div class="row">
    <div class="col-md-3">
        <img src="<?php echo Url::to(['/site/photo','rm'=>$model['u_rm']]) ?>" style="height:auto; width:100%;">
    </div>
    <div class="col-md-9">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'NIK',
                    'value'=>$model['u_nik']
                ],
                [
                    'label'=>'Email',
                    'value'=>$model['u_email']
                ],
                [
                    'label'=>'Nama Lengkap',
                    'value'=>$model['u_nama_depan']
                ],
                [
                    'label'=>'Jenis Kelamin',
                    'value'=>$model['u_jkel']=='L' ? 'Laki-laki' : 'Perempuan'
                ],
                [
                    'label'=>'Tempat/Tgl Lahir',
                    'value'=>$model['u_tmpt_lahir'].' / '.date('d-m-Y',strtotime($model['u_tgl_lahir']))
                ],
                [
                    'label'=>'Alamat',
                    'value'=>$model['u_alamat'].', KABUPATEN '.$model['u_kab'].', PROVINSI '.$model['u_provinsi']
                ],
                [
                    'label'=>'No. HP',
                    'value'=>$model['u_no_hp']
                ],
                [
                    'label'=>'Agama',
                    'value'=>$model['agama']!=NULL ? $model['agama']['Agama'] : ''
                ],
                [
                    'label'=>'Status Pernikahan',
                    'value'=>$model['u_status_nikah']!=NULL ? $status_marital[$model['u_status_nikah']] : ''
                ],
                [
                    'label'=>'Kedudukan Keluarga',
                    'value'=>$model['u_kedudukan_keluarga']
                ],
                [
                    'label'=>'Istri Ke',
                    'visible'=>$model['u_kedudukan_keluarga']=='istri' ? true : false,
                    'value'=>$model['u_istri_ke']
                ],
                [
                    'label'=>'Pendidikan',
                    'value'=>$model['u_pendidikan']!=NULL ? $pendidikan[$model['u_pendidikan']] : ''
                ],
                [
                    'label'=>'Pekerjaan',
                    'value'=>$model['pekerjaan']!=NULL ? $model['pekerjaan']['PerkerjaanJabatan'] : ''
                ],
                [
                    'label'=>'Jabatan Pekerjaan',
                    'value'=>$model['u_jabatan_pekerjaan']
                ],
                [
                    'label'=>'Nama Ayah',
                    'value'=>$model['u_nama_ayah']
                ],
                [
                    'label'=>'Nama Ibu',
                    'value'=>$model['u_nama_ibu']
                ],
                [
                    'label'=>'Nama Pasangan',
                    'visible'=>$model['u_status_nikah']=='K' ? true : false,
                    'value'=>$model['u_nama_pasangan']
                ],
                [
                    'label'=>'Peserta Termasuk Anggota Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['u_anggota_darurat']==1 ? true : false,
                    'value'=>$model['u_anggota_darurat']
                ],
                [
                    'label'=>'Nama Tim Penangangan Keadaan Darurat',
                    'visible'=>$model['u_anggota_darurat_ket']!=NULL ? true : false,
                    'value'=>$model['u_anggota_darurat_ket']
                ],
                [
                    'label'=>'Tgl Terakhir MCU',
                    'visible'=>$model['u_tgl_terakhir_mcu']!=NULL ? true : false,
                    'value'=>date('d-m-Y',strtotime($model['u_tgl_terakhir_mcu']))
                ],
                [
                    'label'=>'Nama Dokter',
                    'visible'=>$model['u_dokter']!=NULL ? true : false,
                    'value'=>$model['u_dokter']
                ],
                [
                    'label'=>'Alamat Dokter',
                    'visible'=>$model['u_alamat_dokter']!=NULL ? true : false,
                    'value'=>$model['u_alamat_dokter']
                ]
            ],
        ]) ?>
    </div>
</div>
<?php
if($model['kuisionerbiodata']!=NULL){
    ?>
    <div class="row">
        <div class="col-md-4" style="text-align:center;"><label>Pekerjaan/Perusahaan Sebelum</label><br><?php echo $model['kuisionerbiodata']['ukb_krj_sebelum'].'/'.$model['kuisionerbiodata']['ukb_krj_sebelum_perusahaan']; ?></div>
        <div class="col-md-4" style="text-align:center;"><label>Pekerjaan/Perusahaan Sekarang</label><br><?php echo $model['kuisionerbiodata']['ukb_krj_skrg'].'/'.$model['kuisionerbiodata']['ukb_krj_skrg_perusahaan']; ?></div>
        <div class="col-md-4" style="text-align:center;"><label>Pekerjaan/Perusahaan Dituju</label><br><?php echo $model['kuisionerbiodata']['ukb_krj_dituju'].'/'.$model['kuisionerbiodata']['ukb_krj_dituju_perusahaan']; ?></div>
    </div>
    <?php
    if($model['kuisionerbiodata']['ukb_krj_sebelum']!=""){
        ?><hr>
        <strong>RIWAYAT PEKERJAAN SEBELUMNYA</strong>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Uraian</th>
                    <th>Pekerjaan Utama</th>
                    <th>Pekerjaan Tambahan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_utama_uraian']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_tambah_uraian']; ?></td>
                </tr>
                <tr>
                    <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_utama_target']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_tambah_target']; ?></td>
                </tr>
                <tr>
                    <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_utama_cara']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_tambah_cara']; ?></td>
                </tr>
                <tr>
                    <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_utama_alat']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_sblm_tambah_alat']; ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    if($model['kuisionerbiodata']['ukb_krj_skrg']!=""){
        ?><hr>
        <strong>PEKERJAAN SEKARANG</strong>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Uraian</th>
                    <th>Pekerjaan Utama</th>
                    <th>Pekerjaan Tambahan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_utama_uraian']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_tambah_uraian']; ?></td>
                </tr>
                <tr>
                    <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_utama_target']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_tambah_target']; ?></td>
                </tr>
                <tr>
                    <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_utama_cara']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_tambah_cara']; ?></td>
                </tr>
                <tr>
                    <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_utama_alat']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_skrg_tambah_alat']; ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    if($model['kuisionerbiodata']['ukb_krj_dituju']!=""){
        ?><hr>
        <strong>PEKERJAAN YANG DITUJU/DILAMAR</strong>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Uraian</th>
                    <th>Pekerjaan Utama</th>
                    <th>Pekerjaan Tambahan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Uraian Tugas<br><i>Uraian fungsi dan tanggungjawab dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_utama_uraian']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_tambah_uraian']; ?></td>
                </tr>
                <tr>
                    <td>Target Kerja<br><i>Sasaran yang telah ditetapkan untuk dicapai dalam suatu pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_utama_target']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_tambah_target']; ?></td>
                </tr>
                <tr>
                    <td>Cara Kerja<br><i>Tahapan yang dilakukan sehingga tercapai tujuan pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_utama_cara']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_tambah_cara']; ?></td>
                </tr>
                <tr>
                    <td>Alat Kerja<br><i>Benda yang digunakan untuk mengerjakan sesuatu untuk mempermudah pekerjaan</i></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_utama_alat']; ?></td>
                    <td><?php echo $model['kuisionerbiodata']['ukb_dituju_tambah_alat']; ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}
if(count($kategori)>0){
    function childKuisioner($model,$data,$tab=5,$c=false){
        $no=1;
        foreach($data as $i){
            $child = Kuisioner::find()->where(['k_id_parent'=>$i['k_id']])->asArray()->all();
            $value=UserKuisioner::find()->select('uk_ceklis,uk_keterangan')->where(['u_id'=>$model['u_id'],'k_id'=>$i['k_id']])->asArray()->limit(1)->one();
            ?>
            <tr>
                <td><?php echo ($tab==5 ? $no : ''); ?></td>
                <td style='padding-left:<?php echo $tab ?>px; <?php echo ( count($child)>0 && $c ? "font-weight:bolder;" : "" ); ?>'><?php echo $i['k_isi_indo']; ?></td>
                <td>
                    <?php
                    if(($i['k_id']>=46 && $i['k_id']<=53) || $i['k_id']==57 || $i['k_id']==103 || $i['k_id']==104 ){
                        $v=NULL;
                        if($i['k_id']==103){
                            $t=[1=>'Tidak Sedang Menstruasi','Sedang Menstruasi'];
                            $v=$value!=NULL ? $t[$value['uk_keterangan']] : NULL;
                        }elseif($i['k_id']==104){
                            $t=[1=>'Tidak Sedang Hamil','Sedang Hamil','Sedang Nifas'];
                            $v=$value!=NULL ? $t[$value['uk_keterangan']] : NULL;
                        }else{
                            $v=$value!=NULL ? $value['uk_keterangan'] : NULL;
                        }
                        echo $v;
                    }else{
                        if($value!=NULL){
                            if($value['uk_ceklis']==1){
                                echo "Iya";
                                if(!empty($value['uk_keterangan'])){
                                    echo "<br><i>".$value['uk_keterangan']."</i>";
                                }
                            }else{
                                echo "Tidak";
                            }
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
            if(count($child)>0){
                echo childKuisioner($model,$child,$tab+=20,true);
                $tab-=20;
            }
            $no++;
        }
    }
    foreach($kategori as $k){
        echo "<strong>".strtoupper($k['kk_nama_indo'])."</strong><hr>";
        $data=Kuisioner::find()->where(['kk_id'=>$k['kk_id']])->asArray()->all();
        ?>
        <table class="table table-bordered table-riwayat-penyakit">
            <thead>
                <tr>
                    <th>No</th>
                    <th></th>
                    <th width="20%">Jawaban</th>
                </tr>
            </thead>
            <tbody>
                <?php
                childKuisioner($model,$data);
                ?>
            </tbody>
        </table>
        <?php
    }
}