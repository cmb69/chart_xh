<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\Model\Chart;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Plib\DocumentStore2;
use Plib\View;

class ChartCommandTest extends TestCase
{
    private DocumentStore2 $store;
    private Configurator $configurator;
    private View $view;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->store = new DocumentStore2(vfsStream::url("root/"));
        $chart = Chart::create("test", $this->store);
        $dataset = $chart->addDataset("one", "#ff0000");
        $dataset->addValue(1);
        $dataset->addValue(2);
        $dataset->addValue(3);
        $this->store->commit();
        $this->configurator = new Configurator();
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): ChartCommand
    {
        return new ChartCommand("./", $this->store, $this->configurator, $this->view);
    }

    public function testRendersChart(): void
    {
        $response = $this->sut()("test");
        Approvals::verifyHtml($response->output());
    }

    public function testReportsUnreadableChart(): void
    {
        $response = $this->sut()("wrong");
        $this->assertStringContainsString("Cannot load the chart “wrong”!", $response->output());
    }
}
