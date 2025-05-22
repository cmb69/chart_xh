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
use Plib\DocumentStore2;
use Plib\Response;
use Plib\View;

class ChartCommand
{
    private DocumentStore2 $store;
    private View $view;

    public function __construct(DocumentStore2 $store, View $view)
    {
        $this->store = $store;
        $this->view = $view;
    }

    public function __invoke(string $name): Response
    {
        $chart = Chart::read($name, $this->store);
        if ($chart === null) {
            return Response::create("no such chart");
        }
        return Response::create($this->view->render("chart", [
            "js_conf" => $this->jsConf($chart),
        ]));
    }

    /** @return mixed */
    private function jsConf(Chart $chart)
    {
        $datasets = [];
        foreach ($chart->datasets() as $dataset) {
            $datas = [];
            foreach ($dataset->data() as $data) {
                $datas[] = (object) [
                    "x" => (string) $data->x(),
                    "y" => $data->y(),
                ];
            }
            $datasets[] = ["data" => $datas];
        }
        return (object) [
            "type" => "line",
            "data" => (object) [
                "datasets" => $datasets,
            ],
        ];
    }
}
