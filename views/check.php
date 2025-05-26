<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $message
 * @var list<LibXMLError> $errors
 */
?>

<article class="chart_check">
  <h1>Chart - <?=$this->text("label_check")?></h1>
  <?=$this->raw($message)?>
  <ul>
<?foreach ($errors as $error):?>
    <li><?=$this->esc($error->line)?>:<?=$this->esc($error->column)?>: <?=$this->esc($error->message)?></li>
<?endforeach?>
  </ul>
</article>
