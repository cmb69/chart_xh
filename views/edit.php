<?php

use Chart\Model\Chart;
use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var object{name:string,caption:string,labels:string,datasets:string} $chart
 * @var list<object{label:string,color:string,values:string}> $datasets
 * @var string $name_disabled
 * @var string $token
 * @var string $script
 */
?>

<script type="module" src="<?=$this->esc($script)?>"></script>
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
    <table style="display: none">
      <caption><?=$this->text("label_datasets")?></caption>
      <colgroup>
        <col>
        <col>
        <col>
        <col>
      </colgroup>
      <thead>
        <tr>
          <th><?=$this->text("label_label")?></th>
          <th><?=$this->text("label_color")?></th>
          <th><?=$this->text("label_values")?></th>
          <th><button type="button" class="chart_add_dataset"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="1em" height="1em" fill="currentColor"><title><?=$this->text("label_add_dataset")?></title><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM200 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg></button></th>
        </tr>
      </thead>
      <tbody>
<?foreach ($datasets as $dataset):?>
        <tr>
          <td><input value="<?=$this->esc($dataset->label)?>"></td>
          <td><input type="color" value="<?=$this->esc($dataset->color)?>"></td>
          <td><input value="<?=$this->esc($dataset->values)?>"></td>
          <td></td>
        </tr>
<?endforeach?>
      </tbody>
      <tfoot style="display: none">
        <tr>
          <td><input></td>
          <td><input type="color"></td>
          <td><input></td>
          <td>
            <button type="button" class="chart_move_dataset"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="1em" height="1em" fill="currentColor"><title><?=$this->text("label_move_dataset")?></title><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M350 177.5c3.8-8.8 2-19-4.6-26l-136-144C204.9 2.7 198.6 0 192 0s-12.9 2.7-17.4 7.5l-136 144c-6.6 7-8.4 17.2-4.6 26s12.5 14.5 22 14.5l88 0 0 192c0 17.7-14.3 32-32 32l-80 0c-17.7 0-32 14.3-32 32l0 32c0 17.7 14.3 32 32 32l80 0c70.7 0 128-57.3 128-128l0-192 88 0c9.6 0 18.2-5.7 22-14.5z"/></svg></button>
            <button type="button" class="chart_delete_dataset"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="1em" height="1em" fill="currentColor"><title><?=$this->text("label_delete_dataset")?></title><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg></button>
          </td>
        </tr>
      </tfoot>
    </table>
    <p class="chart_controls">
      <button name="chart_do"><?=$this->text("label_save")?></button>
    </p>
    <input type="hidden" name="chart_token" value="<?=$this->esc($token)?>">
  </form>
</article>
