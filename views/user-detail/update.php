<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserDetail */

$this->title = 'Update User Detail: ' . $model->id_user_detail;
$this->params['breadcrumbs'][] = ['label' => 'User Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_user_detail, 'url' => ['view', 'id' => $model->id_user_detail]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
