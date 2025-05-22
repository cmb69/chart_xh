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
use Plib\CsrfProtector;
use Plib\DocumentStore2 as DocumentStore;
use Plib\Request;
use Plib\Response;
use Plib\View;

class ChartAdminCommand
{
    // private string $pluginFolder;
    private DocumentStore $store;
    private CsrfProtector $csrfProtector;
    private View $view;

    public function __construct(
        // string $pluginFolder,
        DocumentStore $store,
        CsrfProtector $csrfProtector,
        View $view
    ) {
        // $this->pluginFolder = $pluginFolder;
        $this->store = $store;
        $this->csrfProtector = $csrfProtector;
        $this->view = $view;
    }

    public function __invoke(Request $request): Response
    {
        switch ($request->get("action")) {
            default:
                return $this->overview($request);
            case "create":
                return $this->new($request);
            case "update":
                return $this->edit($request);
        }
    }

    private function overview(Request $request): Response
    {
        return $this->respondWithOverview($request);
    }

    private function new(Request $request): Response
    {
        if ($request->post("chart_do") !== null) {
            return $this->create($request);
        }
        return $this->respondWithEditor($request, true, new Chart("hello", "world"), []);
    }

    private function create(Request $request): Response
    {
        $chart = Chart::create($request->post("name") ?? "", $this->store);
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithEditor($request, true, $chart, [], $errors);
        }
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithEditor($request, true, $chart, [], $errors);
        }
        return Response::redirect($request->url()->without("action")->absolute());
    }

    private function edit(Request $request): Response
    {
        if ($request->post("chart_do") !== null) {
            return $this->update($request);
        }
        if ($request->get("chart_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = Chart::read($request->get("chart_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        return $this->respondWithEditor($request, false, $chart, []);
    }

    private function update(Request $request): Response
    {
        if ($request->get("chart_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = Chart::update($request->get("chart_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithEditor($request, true, $chart, [], $errors);
        }
        $chart->setCaption($request->post("caption") ?? "");
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithEditor($request, false, $chart, [], $errors);
        }
        return Response::redirect($request->url()->without("action")->absolute());
    }

    /** @param list<string> $errors */
    private function respondWithOverview(Request $request, array $errors = []): Response
    {
        $charts = $this->store->find('/[a-z0-9\-]+\.xml$/');
        return Response::create($this->view->render("admin", [
            "errors" => $errors,
            "charts" => $this->chartDtos($request, $charts),
        ]))->withTitle("Chart – " . $this->view->text("menu_main"));
    }

    /**
     * @param list<string> $charts
     * @return list<object{name:string,checked:string}>
     */
    private function chartDtos(Request $request, array $charts): array
    {
        $res = [];
        foreach ($charts as $chart) {
            $name = basename($chart, ".xml");
            $res[] = (object) [
                "name" => $name,
                "checked" => $request->get("chart_name") === $name ? "checked" : "",
            ];
        }
        return $res;
    }

    /**
     * @param iterable<object{latitude:float,longitude:float,info:string,show:string}> $markers
     * @param list<string> $errors
     */
    private function respondWithEditor(
        Request $request,
        bool $new,
        Chart $chart,
        iterable $markers,
        array $errors = []
    ): Response {
        return Response::create($this->view->render("edit", [
            "errors" => $errors,
            "name_disabled" => $new ? "" : "disabled",
            "chart" => $chart,
            "markers" => $markers,
            "token" => $this->csrfProtector->token(),
            // "script" => $request->url()->path($this->script())->with("v", Dic::VERSION)->relative(),
        ]))->withTitle("Chart – " . $this->view->text("label_edit"));
    }
}
