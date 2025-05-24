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

use Plib\DocumentStore2 as DocumentStore;
use Plib\Response;
use Plib\SystemChecker;
use Plib\View;

class InfoCommand
{
    private string $pluginFolder;
    private DocumentStore $store;
    private SystemChecker $systemChecker;
    private View $view;

    public function __construct(
        string $pluginFolder,
        DocumentStore $store,
        SystemChecker $systemChecker,
        View $view
    ) {
        $this->pluginFolder = $pluginFolder;
        $this->store = $store;
        $this->systemChecker = $systemChecker;
        $this->view = $view;
    }

    public function __invoke(): Response
    {
        return Response::create($this->view->render("info", [
            "version" => Dic::VERSION,
            "checks" => [
                $this->checkPhpVersion("8.0.0"),
                $this->checkExtension("DOM"),
                $this->checkXHVersion("1.7.0"),
                $this->checkPlibVersion("1.10"),
                $this->checkWritability($this->pluginFolder . "config/"),
                $this->checkWritability($this->pluginFolder . "css/"),
                $this->checkWritability($this->pluginFolder . "languages/"),
                $this->checkWritability($this->store->folder()),
            ]
        ]))->withTitle("Chart " . $this->view->esc(Dic::VERSION));
    }

    private function checkPhpVersion(string $version): string
    {
        $okay = $this->systemChecker->checkVersion(PHP_VERSION, $version);
        $severity = $okay ? "success" : "fail";
        return $this->view->message($severity, "syscheck_phpversion", $version, $this->state($okay));
    }

    private function checkExtension(string $extension): string
    {
        $okay = $this->systemChecker->checkExtension($extension);
        $severity = $okay ? "success" : "fail";
        return $this->view->message($severity, "syscheck_extension", $extension, $this->state($okay));
    }

    private function checkXHVersion(string $version): string
    {
        $okay = $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $version");
        $severity = $okay ? "success" : "fail";
        return $this->view->message($severity, "syscheck_xhversion", $version, $this->state($okay));
    }

    private function checkPlibVersion(string $version): string
    {
        $okay = $this->systemChecker->checkPlugin("plib", $version);
        $severity = $okay ? "success" : "fail";
        return $this->view->message($severity, "syscheck_plibversion", $version, $this->state($okay));
    }

    private function checkWritability(string $filename): string
    {
        $okay = $this->systemChecker->checkWritability($filename);
        $severity = $okay ? "success" : "fail";
        return $this->view->message($severity, "syscheck_writable", $filename, $this->state($okay));
    }

    private function state(bool $okay): string
    {
        return $okay ? $this->view->plain("syscheck_good") : $this->view->plain("syscheck_bad");
    }
}
