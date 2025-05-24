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

namespace Chart\Model;

use Plib\Document2 as Document;
use Plib\DocumentStore2 as DocumentStore;

final class PowerChart implements Document
{
    /** @var mixed */
    private $jsConf = null;

    public static function new(string $key): self
    {
        return new self();
    }

    public static function fromString(string $contents, string $key): self
    {
        $that = new self();
        $that->jsConf = json_decode($contents, true);
        return $that;
    }

    public static function read(string $name, DocumentStore $store): ?self
    {
        return $store->read("$name.json", self::class);
    }

    /** @return mixed */
    public function jsConf()
    {
        return $this->jsConf;
    }

    public function toString(): string
    {
        return "";
    }
}
