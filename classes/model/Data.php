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

class Data
{
    private string $x;
    private int $y;

    public static function fromXml(DOMElement $elt): self
    {
        return new self(
            $elt->getAttribute("x"),
            (int) $elt->getAttribute("y")
        );
    }

    public function __construct(string $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function x(): string
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    public function toXml(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement("data");
        $elt->setAttribute("x", $this->x);
        $elt->setAttribute("y", (string) $this->y);
        return $elt;
    }
}
