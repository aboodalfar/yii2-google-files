<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'List My Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <div class="col-lg-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Thumbnail Link</th>
                        <th>(Download) Link</th>
                        <th>Modified Date</th>
                        <th>File Size (MB)</th>
                        <th>Owner Names</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $file){ ?>
                        <tr>
                            <td><?= $file['name'] ?></td>
                            <td><img class="img-fluid" src="<?= $file['hasThumbnail'] ?>" /> </td>
                            <td> <a href="<?= $file['exportLinks']['application/pdf'] ?>">Download File</a></td>
                            <td><?= $file['created_at'] ?></td>
                            <td><?= $file['size'] ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                </tbody>
                
            </table>
        </div>
    </div>
</div>
