<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $version
 * @var list<string> $checks
 */
?>

<article class="chart_plugininfo">
  <h1>Chart <?=$this->esc($version)?></h1>
  <section class="chart_syscheck">
    <h2><?=$this->text("syscheck_title")?><h2>
<?foreach ($checks as $check):?>
    <?=$this->raw($check)?>
<?endforeach?>
  </section>
</article>
