<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Chart_XH.
 *
 * Chart_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Chart_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Chart_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chart;

use Chart\Model\Chart;

class Configurator
{
    /** @return mixed */
    public function configure(Chart $chart)
    {
        return [
            "type" => $chart->type(),
            "data" => [
                "labels" => $this->labels($chart),
                "datasets" => $this->datasets($chart),
            ],
            "options" => $this->options($chart),
        ];
    }

    /** @return list<array{label:string,data:mixed,backgroundColor:mixed,borderColor:mixed}> */
    private function datasets(Chart $chart): array
    {
        if (!$chart->transposed()) {
            $datasets = [];
            foreach ($chart->datasets() as $dataset) {
                $datasets[] = [
                    "label" => $dataset->label(),
                    "data" => $dataset->values(),
                    "backgroundColor" => $dataset->color(),
                    "borderColor" => $dataset->color(),
                ];
            }
        } else {
            $datasets = [];
            foreach ($chart->labels() as $i => $label) {
                $colors = [];
                $data = [];
                foreach ($chart->datasets() as $dataset) {
                    $colors[] = $dataset->color();
                    $data[] = $dataset->values()[$i];
                }
                $datasets[] = [
                    "label" => $label,
                    "data" => $data,
                    "backgroundColor" => $colors,
                    "borderColor" => $colors,
                ];
            }
        }
        return $datasets;
    }

    /** @return list<string> */
    private function labels(Chart $chart): array
    {
        if (!$chart->transposed()) {
            return $chart->labels();
        }
        $labels = [];
        foreach ($chart->datasets() as $dataset) {
            $labels[] = $dataset->label();
        }
        return $labels;
    }

    /** @return array<string,mixed> */
    private function options(Chart $chart): array
    {
        $options = [
            "spanGaps" => true,
        ];
        if ($chart->transposed()) {
            $options["plugins"]["legend"]["labels"] = [
                "boxWidth" => 0,
                "boxHeight" => 0,
            ];
        }
        return $options;
    }
}
