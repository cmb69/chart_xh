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

use DOMDocument;
use DOMElement;
use DOMNode;
use Plib\Document2;
use Plib\DocumentStore2;

final class Chart implements Document2
{
    private string $name;
    /** @var list<Data> */
    private array $data = [];

    public static function new(string $key): self
    {
        return new self(basename($key, ".xml"));
    }

    public static function fromString(string $contents, string $key): ?self
    {
        if ($contents === "") {
            return null;
        }
        $doc = new DOMDocument("1.0", "UTF-8");
        if (!$doc->loadXML($contents)) {
            return null;
        }
        if (!$doc->relaxNGValidate(__DIR__ . "/../../chart.rng")) {
            return null;
        }
        assert($doc->documentElement instanceof DOMElement);
        $that = new self(basename($key, ".xml"));
        $chart = $doc->documentElement;
        foreach ($chart->childNodes as $childNode) {
            assert($childNode instanceof DOMNode);
            if ($childNode->nodeName === "data") {
                assert($childNode instanceof DOMElement);
                $that->data[] = Data::fromXml($childNode);
            }
        }
        return $that;
    }

    public static function create(string $name, DocumentStore2 $store): ?self
    {
        return $store->create("$name.xml", self::class);
    }

    public static function read(string $name, DocumentStore2 $store): ?self
    {
        return $store->read("$name.xml", self::class);
    }

    public static function update(string $name, DocumentStore2 $store): ?self
    {
        return $store->update("$name.xml", self::class);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return list<Data> */
    public function data(): array
    {
        return $this->data;
    }

    public function addData(int $x, int $y, string $color): Data
    {
        return $this->data[] = new Data($x, $y, $color);
    }

    public function toString(): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $chart = $doc->createElement('chart');
        $doc->appendChild($chart);
        foreach ($this->data as $data) {
            $chart->appendChild($data->toXml($doc));
        }
        if (!$doc->relaxNGValidate(__DIR__ . "/../../chart.rng")) {
            return "";
        }
        $doc->formatOutput = true;
        return (string) $doc->saveXML();
    }
}
