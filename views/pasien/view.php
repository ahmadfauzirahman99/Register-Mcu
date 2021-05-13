<?php
use yii\widgets\DetailView;
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Detail Pasien</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'NO_PASIEN',
                        [
                            'label'=>'ID/NIK',
                            'value'=>$model['NOIDENTITAS']
                        ],
                        'NAMA',
                        'ALAMAT',
                        'PROVINSI',
                        'KABUPATEN',
                        'KECAMATAN',
                        'KELURAHAN',
                        'PEKERJAAN',
                    ],
                ]) ?>
                </div>
                <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label'=>'Tempat Lahir',
                            'value'=>$model['TP_LAHIR'],
                        ],
                        [
                            'label'=>'Tanggal Lahir',
                            'value'=>function($q){
                                return $q['TGL_LAHIR']!=NULL ? date('d-m-Y',strtotime($q['TGL_LAHIR'])) : NULL;
                            }
                        ],
                        'NO_TELP',
                        'NO_HP',
                        'AGAMA',
                        [
                            'label'=>'Jenis Kelamin',
                            'value'=>function($q){
                                return $q['JENIS_KEL']=='L' ? 'Laki-laki' : 'Perempuan';
                            }
                        ],
                        [
                            'label'=>'Nama Ayah',
                            'value'=>$model['NAMAAYAH']
                        ],
                        [
                            'label'=>'Nama Ibu',
                            'value'=>$model['NAMAIBU']
                        ]
                    ],
                ]) ?>
                </div>
            </div>
            <h4>Riwayat Rawatpoli</h4>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tgl. Masuk</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($model['rawatpoli'])>0){
                        $no=1;
                        foreach($model['rawatpoli'] as $rp){
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo date('d-m-Y H:i:s',strtotime($rp['TANGGAL'])); ?></td>
                                <td><?php echo $rp['KET']; ?></td>
                                <td><?php echo $rp['NAMADOKTER']; ?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                    }else{
                        ?><tr><td colspan="6">Riwayat Rawatpoli Tidak Tersedia</td></tr><?php
                    }
                    ?>
                </tbody>
            </table>
            <h4>Riwayat Rawatinap</h4>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tgl. Masuk</th>
                        <th>Ruang</th>
                        <th>Kamar</th>
                        <th>No. Bed</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($model['rawatinap'])>0){
                        $no=1;
                        foreach($model['rawatinap'] as $ri){
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo date('d-m-Y H:i:s',strtotime($ri['TGL_MASUK'])); ?></td>
                                <td><?php echo $ri['NM_RUANG']; ?></td>
                                <td><?php echo $ri['NAMA_KAMAR']; ?></td>
                                <td><?php echo $ri['NAMAKELAS']; ?></td>
                                <td><?php echo $ri['NO_BED']; ?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                    }else{
                        ?><tr><td colspan="6">Riwayat Rawatinap Tidak Tersedia</td></tr><?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        </div>
    </div>
</div>