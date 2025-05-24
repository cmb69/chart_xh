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
}
