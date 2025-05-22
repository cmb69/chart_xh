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
    private View $view;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->store = new DocumentStore2(vfsStream::url("root/"));
        $chart = Chart::create("test", $this->store);
        $dataset = $chart->addDataset("#ff0000");
        $dataset->addData(1, 1);
        $dataset->addData(2, 2);
        $dataset->addData(3, 3);
        $this->store->commit();
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): ChartCommand
    {
        return new ChartCommand($this->store, $this->view);
    }

    public function testRendersChart(): void
    {
        $response = $this->sut()("test");
        Approvals::verifyHtml($response->output());
    }
}
