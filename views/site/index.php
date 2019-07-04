<?php

$this->registerJsFile(Yii::getAlias('@web/js/scroll.js'), ['depends' => [\yii\web\JqueryAsset::class]]);
/* @var $this yii\web\View */

$this->title = 'Social';
?>

<div class="site-index">
<h1>Posts list</h1>
    <div id="posts"></div>
</div>
<div style="display: none"
     id="loader-manager"
     data-url="/post"
     data-last="0"
</div>
