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
use Plib\DocumentStore2 as DocumentStore;
use Plib\Request;
use Plib\Response;
use Plib\View;

class ChartCommand
{
    private string $pluginFolder;
    /** @var array<string,string> */
    private array $conf;
    private DocumentStore $store;
    private Configurator $configurator;
    private View $view;

    /** @param array<string,string> $conf */
    public function __construct(
        string $pluginFolder,
        array $conf,
        DocumentStore $store,
        Configurator $configurator,
        View $view
    ) {
        $this->pluginFolder = $pluginFolder;
        $this->conf = $conf;
        $this->store = $store;
        $this->configurator = $configurator;
        $this->view = $view;
    }

    public function __invoke(string $name, ?string $caption, Request $request): Response
    {
        if ($caption !== null) {
            $chart = PowerChart::read($name, $this->store);
        } else {
            $chart = Chart::read($name, $this->store);
        }
        if ($chart === null) {
            return Response::create($this->view->message("fail", "error_load", $name));
        }
        return Response::create($this->view->render("chart", [
            "caption" => $caption ?? $chart->caption(),
            "chart_js" => $this->conf["chartjs_url"] ?: $this->pluginFolder . "chartjs/chart.umd.min.js",
            "script" => $request->url()->path($this->script())->with("v", Dic::VERSION)->relative(),
            "js_conf" => $caption !== null
                ? json_decode($chart->json(), true)
                : $this->configurator->configure($chart),
        ]));
    }

    private function script(): string
    {
        if (is_file($this->pluginFolder . "chart.min.js")) {
            return $this->pluginFolder . "chart.min.js";
        }
        return $this->pluginFolder . "chart.js";
    }
}
