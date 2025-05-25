<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var list<string> $charts
 * @var list<string> $powercharts
 * @var string $selected
 * @var string $selected_power
 */
?>

<article class="chart_admin">
  <h1>Chart – <?=$this->text("menu_main")?></h1>
<?foreach ($errors as $error):?>
  <?=$this->raw($error)?>
<?endforeach?>
  <form method="get">
    <input type="hidden" name="selected" value="chart">
    <input type="hidden" name="admin" value="plugin_main">
    <fieldset>
      <legend><?=$this->text("label_charts")?></legend>
      <ul>
<?foreach ($charts as $chart):?>
        <li>
          <label>
            <input type="radio" name="chart_name" value="<?=$this->esc($chart)?>" <?=$this->checked($chart, $selected)?>>
            <span><?=$this->esc($chart)?></span>
          </label>
        </li>
<?endforeach?>
      </ul>
      <p class="chart_controls">
<?if ($charts):?>
        <button name="action" value="update"><?=$this->text("label_edit")?></button>
        <button name="action" value="export"><?=$this->text("label_export")?></button>
<?endif?>
        <button name="action" value="create"><?=$this->text("label_new")?></button>
      </p>
    </fieldset>
    <fieldset>
      <legend><?=$this->text("label_powercharts")?></legend>
      <ul>
<?foreach ($powercharts as $powerchart):?>
        <li>
          <label>
            <input type="radio" name="chart_power_name" value="<?=$this->esc($powerchart)?>" <?=$this->checked($powerchart, $selected_power)?>>
            <span><?=$this->esc($powerchart)?></span>
          </label>
        </li>
<?endforeach?>
      </ul>
      <p class="chart_controls">
<?if ($charts):?>
        <button name="action" value="update_power"><?=$this->text("label_edit")?></button>
<?endif?>
        <button name="action" value="create_power"><?=$this->text("label_new")?></button>
      </p>
    </fieldset>
  </form>
</article>
