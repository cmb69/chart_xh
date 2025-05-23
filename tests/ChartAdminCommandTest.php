<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\Model\Chart;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\CsrfProtector;
use Plib\DocumentStore2 as DocumentStore;
use Plib\FakeRequest;
use Plib\View;

class ChartAdminCommandTest extends TestCase
{
    private DocumentStore $store;
    /** @var CsrfProtector&Stub */
    private $csrfProtector;
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
        $this->store->commit();
        $this->csrfProtector = $this->createStub(CsrfProtector::class);
        $this->csrfProtector->method("token")->willReturn("0123456789ABCDEF");
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): ChartAdminCommand
    {
        return new ChartAdminCommand("./", $this->store, $this->csrfProtector, $this->view);
    }

    public function testRendersOverview(): void
    {
        $request = new FakeRequest();
        $response = $this->sut()($request);
        $this->assertSame("Chart – Administration", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testRendersEditorForNewChart(): void
    {
        $request = new FakeRequest(["url" => "http://example.com/?&chart&admin=plugin_main&action=create"]);
        $response = $this->sut()($request);
        $this->assertSame("Chart – Edit", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testCreatesNewChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertFileExists(vfsStream::url("root/new.xml"));
        $this->assertSame("http://example.com/?&chart&admin=plugin_main", $response->location());
    }

    public function testCreatingIsCsrfProtected(): void
    {
        $this->csrfProtector->method("check")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized to conduct this action!", $response->output());
    }

    public function testReportsFailureToSaveNewChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot save the chart “new”!", $response->output());
    }

    public function testRendersEditorForUpdate(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=test",
        ]);
        $response = $this->sut()($request);
        $this->assertSame("Chart – Edit", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testsReportsThatNoChartIsSelected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testReportsFailureToLoadChart(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=does-not-exist",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testUpdatesChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=test",
            "post" => [
                "chart_do" => "",
                "caption" => "new chart caption",
                "labels" => "one,two,three",
                "datasets" => '[{"label":"one","color":"#ff0000","values":[1,2,3]}]'
            ],
        ]);
        $response = $this->sut()($request);
        $chart = Chart::read("test", $this->store);
        $this->assertSame("new chart caption", $chart->caption());
        $this->assertEquals(["one", "two", "three"], $chart->labels());
        $this->assertCount(1, $chart->datasets());
        $dataset = $chart->datasets()[0];
        $this->assertEquals("one", $dataset->label());
        $this->assertEquals("#ff0000", $dataset->color());
        $this->assertCount(3, $dataset->values());
        $this->assertSame("http://example.com/?&chart&admin=plugin_main&chart_name=test", $response->location());
    }

    public function testsReportsThatNoChartIsSelectedWhenUpdating(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testsReportsFailureToLoadChartWhenUpdating(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=does-not-exist",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testUpdateIsCsrfProtected(): void
    {
        $this->csrfProtector->method("check")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=test",
            "post" => [
                "chart_do" => "",
                "caption" => "a new caption",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized to conduct this action!", $response->output());
    }

    public function testReportsFailureToUpdateChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update&chart_name=test",
            "post" => [
                "chart_do" => "",
                "caption" => "a new caption",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot save the chart “test”!", $response->output());
    }
}
