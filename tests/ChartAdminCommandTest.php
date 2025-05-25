<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\Model\Chart;
use Chart\Model\PowerChart;
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
        $powerchart->setJson("{}");
        $this->store->commit();
        $this->csrfProtector = $this->createStub(CsrfProtector::class);
        $this->csrfProtector->method("token")->willReturn("0123456789ABCDEF");
        $this->configurator = new Configurator();
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["chart"]);
    }

    private function sut(): ChartAdminCommand
    {
        return new ChartAdminCommand("./", $this->store, $this->csrfProtector, $this->configurator, $this->view);
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
        $this->assertSame("http://example.com/?&chart&admin=plugin_main&chart_name=new", $response->location());
    }

    public function testReportsFailureToCreateNewChart(): void
    {
        chmod(vfsStream::url("root"), 0000);
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot create the chart “new”!", $response->output());
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

    public function testRendersExportConfirmation(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=test",
        ]);
        $response = $this->sut()($request);
        $this->assertSame("Chart – Export", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testsReportsThatNoChartIsSelectedForExport(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testReportsFailureToLoadChartForExport(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=does-not-exist",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testExportsChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=test",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $chart = PowerChart::read("test", $this->store);
        $this->assertStringContainsString('"type": "line"', $chart->json());
        $this->assertSame(
            "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_name=test&chart_power_name=test",
            $response->location()
        );
    }

    public function testsReportsThatNoChartIsSelectedWhenExporting(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testsReportsFailureToLoadChartWhenExporting(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=does-not-exist",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testExportingIsCsrfProtected(): void
    {
        $this->csrfProtector->method("check")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=test",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized to conduct this action!", $response->output());
    }

    public function testsReportsFailureToCreateChartWhenExporting(): void
    {
        chmod(vfsStream::url("root/test.json"), 0000);
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=test",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot create the chart “test”!", $response->output());
    }

    public function testReportsFailureToExportChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=export&chart_name=test",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot save the chart “test”!", $response->output());
    }

    public function testRendersEditorForNewPowerChart(): void
    {
        $request = new FakeRequest(["url" => "http://example.com/?&chart&admin=plugin_main&action=create_power"]);
        $response = $this->sut()($request);
        $this->assertSame("Chart – Edit", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testCreatesNewPowerChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create_power",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertFileExists(vfsStream::url("root/new.json"));
        $this->assertSame("http://example.com/?&chart&admin=plugin_main&chart_power_name=new", $response->location());
    }

    public function testReportsFailureToCreatNewPowerChart(): void
    {
        chmod(vfsStream::url("root"), 0000);
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create_power",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertFileDoesNotExist(vfsStream::url("root/new.json"));
        $this->assertStringContainsString("Cannot create the chart “new”!", $response->output());
    }

    public function testCreatingPowerChartIsCsrfProtected(): void
    {
        $this->csrfProtector->method("check")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create_power",
            "post" => [
                "chart_do" => "",
                "name" => "new",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized to conduct this action!", $response->output());
    }

    public function testReportsFailureToSaveNewPowerChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=create_power",
            "post" => [
                "chart_do" => "",
                "name" => "new",
                "json" => "{}",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot save the chart “new”!", $response->output());
    }

    public function testRendersEditorForEditingPowerChart(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=test",
        ]);
        $response = $this->sut()($request);
        $this->assertSame("Chart – Edit", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testsReportsThatNoPowerChartIsSelected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testReportsFailureToLoadPowerChart(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=does-not-exist",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testUpdatesPowerChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=test",
            "post" => [
                "chart_do" => "",
                "json" => '{"type": "line"}',
            ],
        ]);
        $response = $this->sut()($request);
        $chart = PowerChart::read("test", $this->store);
        $this->assertSame('{"type": "line"}', $chart->json());
        $this->assertSame("http://example.com/?&chart&admin=plugin_main&chart_power_name=test", $response->location());
    }

    public function testsReportsThatNoPowerChartIsSelectedWhenUpdating(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You did not select a chart!", $response->output());
    }

    public function testsReportsFailureToLoadPowerChartWhenUpdating(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=does-not-exist",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot load the chart “does-not-exist”!", $response->output());
    }

    public function testUpdatingPowerChartIsCsrfProtected(): void
    {
        $this->csrfProtector->method("check")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=test",
            "post" => [
                "chart_do" => "",
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized to conduct this action!", $response->output());
    }

    public function testReportsFailureToUpdatePowerChart(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&chart&admin=plugin_main&action=update_power&chart_power_name=test",
            "post" => [
                "chart_do" => "",
                "json" => '{"type": "line"}',
            ],
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Cannot save the chart “test”!", $response->output());
    }
}
