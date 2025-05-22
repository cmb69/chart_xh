<?php

namespace Chart\Model;

use PHPUnit\Framework\TestCase;

/** @small */
class ChartTest extends TestCase
{
    public function testDoesRoundTrip(): void
    {
        $chart = new Chart("foo");
        $chart->addData(1, 1, "#ff0000");
        $chart->addData(2, 2, "#00ff00");
        $chart->addData(3, 3, "#0000ff");
        $actual = Chart::fromString($chart->toString(), "foo.xml");
        $this->assertEquals($chart, $actual);
    }
}
