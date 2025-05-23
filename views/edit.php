<?php

use Chart\Model\Chart;
use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var object{name:string,caption:string,labels:string,datasets:string} $chart
 * @var string $name_disabled
 * @var string $token
 * @var string $script
 */
?>

<!-- <script type="module" src="<?//=$this->esc($script)?>"></script>-->
<article class="chart_edit">
  <h1>Chart – <?=$this->text("label_edit")?></h1>
<?foreach ($errors as $error):?>
  <?=$this->raw($error)?>
<?endforeach?>
  <form method="post">
    <p>
      <label>
        <span><?=$this->text("label_name")?></span>
        <span class="chart_help"><?=$this->text("help_name")?></span>
        <input name="name" value="<?=$this->esc($chart->name)?>" <?=$this->esc($name_disabled)?> required pattern="[a-z0-9\-]+">
      </label>
    </p>
    <p>
      <label>
        <span><?=$this->text("label_caption")?></span>
        <textarea name="caption"><?=$this->esc($chart->caption)?></textarea>
      </label>
    </p>
    <p class="chart_labels">
      <label>
        <span><?=$this->text("label_labels")?></span>
        <span class="chart_help"><?=$this->text("help_labels")?></span>
        <textarea name="labels"><?=$this->esc($chart->labels)?></textarea>
      </label>
    </p>
    <p class="chart_datasets">
      <label>
        <span><?=$this->text("label_datasets")?></span>
        <textarea name="datasets"><?=$this->esc($chart->datasets)?></textarea>
      </label>
    </p>
    <p class="chart_controls">
      <button name="chart_do"><?=$this->text("label_save")?></button>
    </p>
    <input type="hidden" name="chart_token" value="<?=$this->esc($token)?>">
  </form>
</article>
