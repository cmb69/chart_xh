<?php

use Plib\View;

/**
 * @var View $this
 * @var mixed $js_conf
 */
?>

<script type="module" src="./plugins/chart/chartjs/chart.umd.js"></script>
<script type="module" src="./plugins/chart/chart.js"></script>
<canvas id="chart" width="400" height="400" data-chart-config='<?=$this->json($js_conf)?>'></canvas>
