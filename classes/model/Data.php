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
    private int $x;
    private int $y;
    private string $color;

    public static function fromXml(DOMElement $elt): self
    {
        return new self(
            (int) $elt->getAttribute("x"),
            (int) $elt->getAttribute("y"),
            $elt->getAttribute("color")
        );
    }

    public function __construct(int $x, int $y, string $color)
    {
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function toXml(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement("data");
        $elt->setAttribute("x", (string) $this->x);
        $elt->setAttribute("y", (string) $this->y);
        $elt->setAttribute("color", $this->color);
        return $elt;
    }
}
