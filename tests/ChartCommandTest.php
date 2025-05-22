<?php

namespace Chart;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\View;

class ChartCommandTest extends TestCase
{
    private View $view;

    public function setUp(): void
    {
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): ChartCommand
    {
        return new ChartCommand($this->view);
    }

    public function testRendersChart(): void
    {
        $response = $this->sut()();
        Approvals::verifyHtml($response->output());
    }
}
