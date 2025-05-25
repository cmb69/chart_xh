<?php

namespace Chart;

use ApprovalTests\Approvals;
use Chart\Model\Chart;
use PHPUnit\Framework\TestCase;
use Plib\DocumentStore2 as DocumentStore;

/** @medium */
class ConfiguratorTest extends TestCase
{
    private DocumentStore $store;

    public function setUp(): void
    {
        $this->store = new DocumentStore("./examples/");
    }

    private function sut(): Configurator
    {
        return new Configurator();
    }

    public function testConfiguresChart(): void
    {
        $chart = Chart::read("voting", $this->store);
        Approvals::verifyAsJson($this->sut()->configure($chart));
    }

    public function testConfiguresTransposedChart(): void
    {
        $chart = Chart::read("voting", $this->store);
        $chart->setTransposed(true);
        Approvals::verifyAsJson($this->sut()->configure($chart));
    }

    public function testConfiguresHorizontalBarChart(): void
    {
        $chart = new Chart("horz-bar", "Horizontal Bar Chart", "horizontal-bar", false, "3/2");
        $conf = $this->sut()->configure($chart);
        $this->assertSame("bar", $conf["type"]);
        $this->assertSame("y", $conf["options"]["indexAxis"]);
    }

    public function testConfiguresHalfPie(): void
    {
        $chart = new Chart("half-pie", "Semi-Pie Chart", "semi-pie", false, "3/2");
        $conf = $this->sut()->configure($chart);
        $this->assertSame("pie", $conf["type"]);
        $this->assertSame(-90, $conf["options"]["rotation"]);
        $this->assertSame(180, $conf["options"]["circumference"]);
    }

    public function testConfiguresHalfDoughnut(): void
    {
        $chart = new Chart("half-doughnut", "Semi-Doughnut Chart", "semi-doughnut", false, "3/2");
        $conf = $this->sut()->configure($chart);
        $this->assertSame("doughnut", $conf["type"]);
        $this->assertSame(-90, $conf["options"]["rotation"]);
        $this->assertSame(180, $conf["options"]["circumference"]);
    }

    public function testConfiguresPolarArea(): void
    {
        $chart = new Chart("polar-area", "Polar Area Chart", "polar-area", false, "3/2");
        $conf = $this->sut()->configure($chart);
        $this->assertSame("polarArea", $conf["type"]);
    }

    public function testNonRationalAspectRatioDefaultsToOne(): void
    {
        $chart = new Chart("polar-area", "Polar Area Chart", "polar-area", false, "17");
        $conf = $this->sut()->configure($chart);
        $this->assertSame(1.0, $conf["options"]["aspectRatio"]);
    }
}
