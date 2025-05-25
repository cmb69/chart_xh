<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\Model\Chart;
use Chart\Model\PowerChart;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Plib\DocumentStore2 as DocumentStore;
use Plib\FakeRequest;
use Plib\View;

class ChartCommandTest extends TestCase
{
    private DocumentStore $store;
    private Configurator $configurator;
    private View $view;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->store = new DocumentStore(vfsStream::url("root/"));
        $chart = Chart::create("test", $this->store);
        $dataset = $chart->addDataset("one", "#ff0000");
        $dataset->addValue(1);
        $dataset->addValue(2);
        $dataset->addValue(3);
        $powerchart = PowerChart::create("test", $this->store);
        $powerchart->setJson('{"type": "bar"}');
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
        $request = new FakeRequest();
        $response = $this->sut()("test", null, $request);
        Approvals::verifyHtml($response->output());
    }

    public function testReportsUnreadableChart(): void
    {
        $request = new FakeRequest();
        $response = $this->sut()("wrong", null, $request);
        $this->assertStringContainsString("Cannot load the chart “wrong”!", $response->output());
    }

    public function testRendersPowerChart(): void
    {
        $request = new FakeRequest();
        $response = $this->sut()("test", "A Power Chart", $request);
        Approvals::verifyHtml($response->output());
    }
}
