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

use Plib\CsrfProtector;
use Plib\DocumentStore2 as DocumentStore;
use Plib\SystemChecker;
use Plib\View;

class Dic
{
    public const VERSION = "1.0-dev";

    public static function chartCommand(): ChartCommand
    {
        global $pth;
        return new ChartCommand(
            $pth["folder"]["plugins"] . "chart/",
            self::documentStore(),
            self::view()
        );
    }

    public static function infoCommand(): InfoCommand
    {
        global $pth;
        return new InfoCommand(
            $pth["folder"]["plugins"] . "chart/",
            self::documentStore(),
            new SystemChecker(),
            self::view()
        );
    }

    public static function chartAdminCommand(): ChartAdminCommand
    {
        global $pth;
        return new ChartAdminCommand(
            $pth["folder"]["plugins"] . "chart/",
            self::documentStore(),
            new CsrfProtector(),
            self::view()
        );
    }

    private static function documentStore(): DocumentStore
    {
        global $pth;
        return new DocumentStore($pth["folder"]["content"] . "chart/");
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;
        return new View($pth["folder"]["plugins"] . "chart/views/", $plugin_tx["chart"]);
    }
}
