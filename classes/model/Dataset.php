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

class Dataset
{
    private string $color;
    /** @var list<Data> */
    private array $data;

    public static function fromXml(DOMElement $elt): self
    {
        $that = new self(
            $elt->getAttribute("color")
        );
        foreach ($elt->childNodes as $childNode) {
            assert($childNode instanceof DOMNode);
            if ($childNode->nodeName === "data") {
                assert($childNode instanceof DOMElement);
                $that->data[] = Data::fromXml($childNode);
            }
        }
        return $that;
    }

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function color(): string
    {
        return $this->color;
    }

    /** @return list<Data> */
    public function data(): array
    {
        return $this->data;
    }

    public function addData(string $x, int $y): Data
    {
        return $this->data[] = new Data($x, $y);
    }

    public function toXml(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement("dataset");
        $elt->setAttribute("color", $this->color);
        foreach ($this->data as $data) {
            $elt->appendChild($data->toXml($doc));
        }
        return $elt;
    }
}
