<?php
use app\models\TindKel;
?>
<h4>Layanan yang diberikan : </h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align:center;">Tindakan</th>
            <th style="text-align:center;">Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($detail)>0){
            $total=0;
            foreach($detail as $d){
                $total+=$d['harga'];
                ?>
                <tr>
                    <td><?php echo $d['nama_tindakan']; ?></td>
                    <td align="right"><?php echo number_format($d['harga'],0,',','.'); ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td align="center"><b>Total : </b></td>
                <td align="right"><?php echo number_format($total,0,',','.'); ?></td>
            </tr>
            <?php
        }else{
            ?><tr><td colspan="2">Detail tindakan kosong</td></tr><?php
        }
        ?>
    </tbody>
</table>