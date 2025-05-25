<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var list<string> $errors
 * @var string $name
 * @var string $token
 */
?>

<article class="chart_export">
  <h1>Chart – <?=$this->text("label_export")?></h1>
<?foreach ($errors as $error):?>
  <?=$this->raw($error)?>
<?endforeach?>
  <form method="post">
    <p><?=$this->text("message_confirm_export", $name)?></p>
    <p class="xh_warning"><?=$this->text("message_export_overwrite", $name)?></p>
    <p class="chart_controls">
      <button name="chart_do" ><?=$this->text("label_export")?></button>
    </p>
    <input type="hidden" name="chart_token" value="<?=$this->esc($token)?>">
  </form>
</article>
