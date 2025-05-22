<?php

namespace Chart\Model;

use PHPUnit\Framework\TestCase;

/** @small */
class ChartTest extends TestCase
{
    public function testDoesRoundTrip(): void
    {
        $chart = new Chart("foo");
        $dataset = $chart->addDataset("#ff0000");
        $dataset->addData(2, 2);
        $dataset->addData(3, 3);
        $actual = Chart::fromString($chart->toString(), "foo.xml");
        $this->assertEquals($chart, $actual);
    }
}
