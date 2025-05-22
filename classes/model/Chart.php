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
    private string $caption;
    /** @var list<string> */
    private array $labels = [];
    /** @var list<Dataset> */
    private array $datasets = [];

    public static function new(string $key): self
    {
        return new self(basename($key, ".xml"), "");
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
        $chart = $doc->documentElement;
        $that = new self(basename($key, ".xml"), $chart->getAttribute("caption"));
        foreach ($chart->childNodes as $childNode) {
            assert($childNode instanceof DOMNode);
            if ($childNode->nodeName === "label") {
                assert($childNode instanceof DOMElement);
                $that->labels[] = (string) $childNode->nodeValue;
            } elseif ($childNode->nodeName === "dataset") {
                assert($childNode instanceof DOMElement);
                $that->datasets[] = Dataset::fromXml($childNode);
            }
        }
        return $that;
    }

    public static function create(string $name, DocumentStore2 $store): self
    {
        $that = $store->create("$name.xml", self::class);
        assert($that instanceof self);
        return $that;
    }

    public static function read(string $name, DocumentStore2 $store): ?self
    {
        return $store->read("$name.xml", self::class);
    }

    public static function update(string $name, DocumentStore2 $store): ?self
    {
        return $store->update("$name.xml", self::class);
    }

    public function __construct(string $name, string $caption)
    {
        $this->name = $name;
        $this->caption = $caption;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function caption(): string
    {

        return $this->caption;
    }

    /** @return list<string> */
    public function labels(): array
    {
        return $this->labels;
    }

    /** @return list<Dataset> */
    public function datasets(): array
    {
        return $this->datasets;
    }

    public function setCaption(string $caption): void
    {
        $this->caption = $caption;
    }

    public function addLabel(string $label): void
    {
        $this->labels[] = $label;
    }

    public function addDataset(string $label, string $color): Dataset
    {
        return $this->datasets[] = new Dataset($label, $color);
    }

    public function toString(): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $chart = $doc->createElement('chart');
        $chart->setAttribute("caption", $this->caption);
        $doc->appendChild($chart);
        foreach ($this->labels as $label) {
            $elt = $doc->createElement("label");
            $elt->nodeValue = $label;
            $chart->appendChild($elt);
        }
        foreach ($this->datasets as $dataset) {
            $chart->appendChild($dataset->toXml($doc));
        }
        if (!$doc->relaxNGValidate(__DIR__ . "/../../chart.rng")) {
            return "";
        }
        $doc->formatOutput = true;
        return (string) $doc->saveXML();
    }
}
