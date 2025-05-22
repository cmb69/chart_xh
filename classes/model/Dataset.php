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
    /** @var list<?int> */
    private array $values;

    public static function fromXml(DOMElement $elt): self
    {
        $that = new self(
            $elt->getAttribute("color")
        );
        foreach ($elt->childNodes as $childNode) {
            assert($childNode instanceof DOMNode);
            if ($childNode->nodeName === "value") {
                assert($childNode instanceof DOMElement);
                $value = $childNode->nodeValue;
                $value = $value === null || $value === "" ? null : (int) $value;
                $that->values[] = $value;
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

    /** @return list<?int> */
    public function values(): array
    {
        return $this->values;
    }

    public function addData(?int $value): void
    {
        $this->values[] = $value;
    }

    public function toXml(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement("dataset");
        $elt->setAttribute("color", $this->color);
        foreach ($this->values as $value) {
            $child = $doc->createElement("value");
            $child->nodeValue = (string) $value;
            $elt->appendChild($child);
        }
        return $elt;
    }
}
