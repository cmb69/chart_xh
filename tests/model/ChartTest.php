<?php

namespace Chart\Model;

use PHPUnit\Framework\TestCase;

/** @small */
class ChartTest extends TestCase
{
    public function testDoesRoundTrip(): void
    {
        $chart = new Chart("foo", "Chart");
        $dataset = $chart->addDataset("bar", "#ff0000");
        $dataset->addValue(2);
        $dataset->addValue(3);
        $actual = Chart::fromString($chart->toString(), "foo.xml");
        $this->assertEquals($chart, $actual);
    }
}
