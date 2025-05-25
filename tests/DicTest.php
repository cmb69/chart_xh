<?php

namespace Chart;

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pth, $plugin_cf, $plugin_tx;
        $pth = ["folder" => ["content" => "", "plugins" => ""]];
        $plugin_cf = ["chart" => []];
        $plugin_tx = ["chart" => []];
    }

    public function testMakesChartCommand(): void
    {
        $this->assertInstanceOf(ChartCommand::class, Dic::chartCommand());
    }

    public function testMakesInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Dic::infoCommand());
    }

    public function testMakesChartAdminCommand(): void
    {
        $this->assertInstanceOf(ChartAdminCommand::class, Dic::chartAdminCommand());
    }
}
