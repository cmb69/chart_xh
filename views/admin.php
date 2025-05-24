<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var list<string> $charts
 * @var string $selected
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
      <button name="action" value="update"><?=$this->text("label_edit")?></button>
      <button name="action" value="create"><?=$this->text("label_new")?></button>
    </p>
  </form>
</article>
