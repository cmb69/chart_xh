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

use Chart\Dto\ChartDto;
use Chart\Model\Chart;
use Chart\Model\PowerChart;
use Plib\CsrfProtector;
use Plib\DocumentStore2 as DocumentStore;
use Plib\Request;
use Plib\Response;
use Plib\View;

class ChartAdminCommand
{
    private string $pluginFolder;
    private DocumentStore $store;
    private CsrfProtector $csrfProtector;
    private Configurator $configurator;
    private View $view;

    public function __construct(
        string $pluginFolder,
        DocumentStore $store,
        CsrfProtector $csrfProtector,
        Configurator $configurator,
        View $view
    ) {
        $this->pluginFolder = $pluginFolder;
        $this->store = $store;
        $this->csrfProtector = $csrfProtector;
        $this->configurator = $configurator;
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
            case "export":
                return $this->export($request);
            case "create_power":
                return $this->newPower($request);
            case "update_power":
                return $this->editPower($request);
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
        $dto = new ChartDto("", "", Chart::TYPES[0], false, "3/2", "", "[]");
        return $this->respondWithEditor($request, true, $dto, []);
    }

    private function create(Request $request): Response
    {
        $chart = Chart::create($request->post("name") ?? "", $this->store);
        $dto = $this->requestToDto($request);
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithEditor($request, true, $dto, [], $errors);
        }
        $this->updateChartFromDto($chart, $dto);
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithEditor($request, true, $dto, [], $errors);
        }
        return Response::redirect($request->url()->without("action")
            ->with("chart_name", $chart->name())->absolute());
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
        return $this->respondWithEditor($request, false, $this->chartToDto($chart), $this->datasetDtos($chart));
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
        $dto = $this->requestToDto($request);
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithEditor($request, true, $dto, [], $errors);
        }
        $this->updateChartFromDto($chart, $dto);
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithEditor($request, false, $dto, [], $errors);
        }
        return Response::redirect($request->url()->without("action")->absolute());
    }

    private function export(Request $request): Response
    {
        if ($request->post("chart_do") !== null) {
            return $this->doExport($request);
        }
        if ($request->get("chart_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = Chart::read($request->get("chart_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        return $this->respondWithExportConfirmation($chart->name());
    }

    private function doExport(Request $request): Response
    {
        if ($request->get("chart_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = Chart::read($request->get("chart_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithExportConfirmation($chart->name(), $errors);
        }
        $powerchart = PowerChart::create($chart->name(), $this->store)
            ?? PowerChart::update($chart->name(), $this->store);
        if ($powerchart === null) {
            return Response::create("cannot create power chart");
        }
        $conf = $this->configurator->configure($chart);
        $powerchart->setJson((string) json_encode(
            $conf,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        ));
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $powerchart->name())];
            return $this->respondWithExportConfirmation($powerchart->name(), $errors);
        }
        return Response::redirect($request->url()->with("action", "update_power")
            ->with("chart_power_name", $powerchart->name())->absolute());
    }

    /** @param list<string> $errors */
    private function respondWithOverview(Request $request, array $errors = []): Response
    {
        $charts = $this->store->find('/^[a-z0-9\-]+\.xml$/');
        $powercharts = $this->store->find('/^[a-z0-9\-]+\.json$/');
        return Response::create($this->view->render("admin", [
            "errors" => $errors,
            "charts" => array_map(fn ($chart) => basename($chart, ".xml"), $charts),
            "powercharts" => array_map(fn ($chart) => basename($chart, ".json"), $powercharts),
            "selected" => $request->get("chart_name") ?? "",
            "selected_power" => $request->get("chart_power_name") ?? "",
        ]))->withTitle("Chart – " . $this->view->text("menu_main"));
    }

    /**
     * @param iterable<object{label:string,color:string,values:string}> $datasets
     * @param list<string> $errors
     */
    private function respondWithEditor(
        Request $request,
        bool $new,
        ChartDto $dto,
        iterable $datasets,
        array $errors = []
    ): Response {
        return Response::create($this->view->render("edit", [
            "errors" => $errors,
            "name_disabled" => $new ? "" : "disabled",
            "chart" => $dto,
            "chart_types" => Chart::TYPES,
            "datasets" => $datasets,
            "token" => $this->csrfProtector->token(),
            "script" => $request->url()->path($this->script())->with("v", Dic::VERSION)->relative(),
        ]))->withTitle("Chart – " . $this->view->text("label_edit"));
    }

    private function chartToDto(Chart $chart): ChartDto
    {
        $datasets = [];
        foreach ($chart->datasets() as $dataset) {
            $datasets[] = [
                "label" => $dataset->label(),
                "color" => $dataset->color(),
                "values" => $dataset->values(),
            ];
        }
        return new ChartDto(
            $chart->name(),
            $chart->caption(),
            $chart->type(),
            $chart->transposed(),
            $chart->aspectRatio(),
            implode(",", $chart->labels()),
            (string) json_encode($datasets)
        );
    }

    /** @return iterable<object{label:string,color:string,values:string}> */
    private function datasetDtos(Chart $chart): iterable
    {
        foreach ($chart->datasets() as $dataset) {
            yield (object) [
                "label" => $dataset->label(),
                "color" => $dataset->color(),
                "values" => implode(",", $dataset->values()),
            ];
        }
    }

    private function requestToDto(Request $request): ChartDto
    {
        return new ChartDto(
            $request->post("name") ?? $request->get("maps_map") ?? "",
            $request->post("caption") ?? "",
            $request->post("type") ?? Chart::TYPES[0],
            $request->post("transposed") !== null,
            $request->post("aspect_ratio") ?? "",
            $request->post("labels") ?? "",
            $request->post("datasets") ?? ""
        );
    }

    private function updateChartFromDto(Chart $chart, ChartDto $dto): void
    {
        $chart->setCaption($dto->caption);
        $chart->setType($dto->type);
        $chart->setTransposed($dto->transposed);
        $chart->setAspectRatio($dto->aspect_ratio);
        $chart->purgeLabels();
        foreach (array_map("trim", explode(",", $dto->labels)) as $label) {
            $chart->addLabel($label);
        }
        $datasets = json_decode($dto->datasets, true);
        if (is_array($datasets)) {
            $chart->purgeDatasets();
            foreach ($datasets as $ds) {
                if (
                    array_key_exists("label", $ds)
                    && is_string($ds["label"])
                    && array_key_exists("color", $ds)
                    && is_string($ds["color"])
                    && array_key_exists("values", $ds)
                    && is_array($ds["values"])
                ) {
                    $dataset = $chart->addDataset($ds["label"], $ds["color"]);
                    foreach ($ds["values"] as $value) {
                        if (is_float($value) || is_int($value) || is_null($value)) {
                            $dataset->addValue($value);
                        }
                    }
                }
            }
        }
    }

    private function script(): string
    {
        return $this->pluginFolder . "admin.js";
    }

    /** @param list<string> $errors */
    private function respondWithExportConfirmation(string $name, array $errors = []): Response
    {
        return Response::create($this->view->render("export", [
            "errors" => $errors,
            "name" => $name,
            "token" => $this->csrfProtector->token(),
        ]))->withTitle("Chart – " . $this->view->text("label_export"));
    }

    private function newPower(Request $request): Response
    {
        if ($request->post("chart_do") !== null) {
            return $this->createPower($request);
        }
        return $this->respondWithPowerEditor("", "");
    }

    private function createPower(Request $request): Response
    {
        $chart = PowerChart::create($request->post("name") ?? "", $this->store);
        if ($chart === null) {
            return Response::create("cannot create power chart");
        }
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithPowerEditor($chart->name(), $request->post("json") ?? "", $errors);
        }
        $chart->setJson($request->post("json") ?? "");
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithPowerEditor($chart->name(), $request->post("json") ?? "", $errors);
        }
        return Response::redirect($request->url()->without("action")
            ->with("chart_power_name", $chart->name())->absolute());
    }

    private function editPower(Request $request): Response
    {
        if ($request->post("chart_do") !== null) {
            return $this->updatePower($request);
        }
        if ($request->get("chart_power_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = PowerChart::read($request->get("chart_power_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_power_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        return $this->respondWithPowerEditor($chart->name(), $chart->json());
    }

    private function updatePower(Request $request): Response
    {
        if ($request->get("chart_power_name") === null) {
            return $this->respondWithOverview($request, [$this->view->message("fail", "error_no_chart")]);
        }
        $chart = PowerChart::update($request->get("chart_power_name"), $this->store);
        if ($chart === null) {
            $errors = [$this->view->message("fail", "error_load", $request->get("chart_power_name"))];
            return $this->respondWithOverview($request, $errors);
        }
        if (!$this->csrfProtector->check($request->post("chart_token"))) {
            $this->store->rollback();
            $errors = [$this->view->message("fail", "error_not_authorized")];
            return $this->respondWithPowerEditor($chart->name(), $request->post("json") ?? "", $errors);
        }
        $chart->setJson($request->post("json") ?? "");
        if (!$this->store->commit()) {
            $errors = [$this->view->message("fail", "error_save", $chart->name())];
            return $this->respondWithPowerEditor($chart->name(), $request->post("json") ?? "", $errors);
        }
        return Response::redirect($request->url()->without("action")->absolute());
    }

    /** @param list<string> $errors */
    private function respondWithPowerEditor(string $name, string $json, array $errors = []): Response
    {
        if (function_exists("init_codeeditor") && defined("CODEEDITOR_VERSION")) {
            init_codeeditor(["chart_json"], '{"mode": {"name": "javascript", "json": true}, "theme": "%THEME%"}');
        }
        return Response::create($this->view->render("power_edit", [
            "errors" => $errors,
            "name_disabled" => $name === "" ? "" : "disabled",
            "name" => $name,
            "json" => $json,
            "token" => $this->csrfProtector->token(),
        ]))->withTitle("Chart – " . $this->view->text("label_edit"));
    }
}
