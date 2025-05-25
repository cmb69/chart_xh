<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var string $name_disabled
 * @var string $name
 * @var string $json
 * @var string $token
 */
?>

<article class="chart_edit_power">
  <h1>Chart – <?=$this->text("label_edit")?></h1>
<?foreach ($errors as $error):?>
  <?=$this->raw($error)?>
<?endforeach?>
  <form method="post">
    <p>
      <label>
        <span><?=$this->text("label_name")?></span>
        <span class="chart_help"><?=$this->text("help_name")?></span>
        <input name="name" value="<?=$this->esc($name)?>" <?=$this->esc($name_disabled)?> required pattern="[a-z0-9\-]+">
      </label>
    </p>
    <p>
      <label>
        <span><?=$this->text("label_json")?></span>
        <textarea class="chart_json" name="json" required><?=$this->esc($json)?></textarea>
      </label>
    </p>
    <p class="chart_controls">
      <button name="chart_do"><?=$this->text("label_save")?></button>
    </p>
    <input type="hidden" name="chart_token" value="<?=$this->esc($token)?>">
  </form>
</article>
