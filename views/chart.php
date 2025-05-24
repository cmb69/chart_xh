<?php

use Plib\View;

/**
 * @var View $this
 * @var string $caption
 * @var string $chart_js
 * @var string $script
 * @var mixed $js_conf
 */
?>

<script type="module" src="<?=$this->esc($chart_js)?>"></script>
<script type="module" src="<?=$this->esc($script)?>"></script>
<figure class="chart_chart" data-chart-config='<?=$this->json($js_conf)?>'>
  <figcaption><?=$this->raw($caption)?></figcaption>
  <canvas width="400" height="400"></canvas>
</figure>
