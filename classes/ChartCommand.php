<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Chat_XH.
 *
 * Chat_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Chat_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Chat_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chart;

use Chart\Model\Chart;
use Chart\Model\PowerChart;
use Plib\DocumentStore2;
use Plib\Response;
use Plib\View;

class ChartCommand
{
    private string $pluginFolder;
    private DocumentStore2 $store;
    private View $view;

    public function __construct(string $pluginFolder, DocumentStore2 $store, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->store = $store;
        $this->view = $view;
    }

    public function __invoke(string $name, bool $advanced = false): Response
    {
        if ($advanced) {
            $chart = PowerChart::read($name, $this->store);
        } else {
            $chart = Chart::read($name, $this->store);
        }
        if ($chart === null) {
            return Response::create($this->view->message("fail", "error_load", $name));
        }
        return Response::create($this->view->render("chart", [
            "caption" => $advanced ? "" : $chart->caption(),
            "chart_js" => $this->pluginFolder . "chartjs/chart.umd.js",
            "script" => $this->pluginFolder . "chart.js",
            "js_conf" => $advanced ? $chart->jsConf() : $this->jsConf($chart),
        ]));
    }

    /** @return mixed */
    private function jsConf(Chart $chart)
    {
        if ($chart->transposed()) {
            $labels = [];
            $datasets = [];
            foreach ($chart->labels() as $i => $label) {
                $colors = [];
                $data = [];
                foreach ($chart->datasets() as $j => $dataset) {
                    $colors[] = $dataset->color();
                    $data[] = $dataset->values()[$i];
                    if ($i === 0) {
                        $labels[] = $dataset->label();
                    }
                }
                $datasets[] = [
                    "backgroundColor" => $colors,
                    "borderColor" => $colors,
                    "data" => $data,
                    "label" => $label,
                ];
            }
        } else {
            $labels = $chart->labels();
            $datasets = [];
            foreach ($chart->datasets() as $dataset) {
                $datasets[] = [
                    "label" => $dataset->label(),
                    "backgroundColor" => $dataset->color(),
                    "borderColor" => $dataset->color(),
                    "data" => $dataset->values(),
                ];
            }
        }
        $options = [
            "spanGaps" => true,
        ];
        if ($chart->transposed()) {
            $options["plugins"]["legend"]["labels"] = [
                "boxWidth" => 0,
                "boxHeight" => 0,
            ];
        }
        return [
            "type" => $chart->type(),
            "data" => [
                "labels" => $labels,
                "datasets" => $datasets,
            ],
            "options" => $options,
        ];
    }
}
