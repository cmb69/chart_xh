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

new Chart(document.getElementById("chart").getContext("2d"), {
    "type": "bar",
    "data": {
        "datasets": [{
            "data": [{"x": "Red", "y": 12},
                {"x": "Blue", "y": 19},
                {"x": "Yellow", "y": 3},
                {"x": "Green", "y": 5},
                {"x": "Purple", "y": 2},
                {"x": "Orange", "y": 3}],
            "backgroundColor": [
                "#ff0000",
                "rgba(54, 162, 235, 0.2)",
                "rgba(255, 206, 86, 0.2)",
                "rgba(75, 192, 192, 0.2)",
                "rgba(153, 102, 255, 0.2)",
                "rgba(255, 159, 64, 0.2)"
            ]
        }]
    },
    "options": {
      "plugins": {
        "legend": {
          "display": false
        }
      }
    }
});
