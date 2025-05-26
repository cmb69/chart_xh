<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\InfoCommand;
use PHPUnit\Framework\TestCase;
use Plib\DocumentStore2 as DocumentStore;
use Plib\FakeSystemChecker;
use Plib\SystemChecker;
use Plib\View;

class InfoCommandTest extends TestCase
{
    private DocumentStore $store;
    private SystemChecker $systemChecker;
    private View $view;

    public function setUp(): void
    {
        $this->store = new DocumentStore("./");
        $this->systemChecker = new FakeSystemChecker();
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): InfoCommand
    {
        return new InfoCommand("./", $this->store, $this->systemChecker, $this->view);
    }

    public function testRendersPluginInfo(): void
    {
        $response = $this->sut()();
        $this->assertSame("Chart 1.0", $response->title());
        Approvals::verifyHtml($response->output());
    }
}
