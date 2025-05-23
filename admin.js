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

function moveDataset(event) {
    const tr = event.currentTarget.parentElement.parentElement;
    tr.parentElement.insertBefore(tr, tr.previousElementSibling);
}

function deleteDataset(event) {
    const tr = event.currentTarget.parentElement.parentElement;
    tr.parentElement.removeChild(tr);
}

const textarea = document.querySelector("textarea[name=datasets]");
textarea.parentElement.style.display = "none";
const form = textarea.form;
const table = form.querySelector("table");
table.style.display = "table";
const buttons = table.rows[table.rows.length - 1].cells[3].innerHTML;
for (let i = 1; i < table.rows.length - 1; i++) {
    table.rows[i].cells[3].innerHTML = buttons;
}
const button = form.querySelector(".chart_add_dataset");
button.onclick = () => {
    const clone = table.rows[table.rows.length - 1].cloneNode(true);
    table.tBodies[0].appendChild(clone);
    clone.querySelector(".chart_move_dataset").onclick = moveDataset;
    clone.querySelector(".chart_delete_dataset").onclick = deleteDataset;
}
form.querySelectorAll(".chart_move_dataset").forEach(button => {
    button.onclick = moveDataset;
});
form.querySelectorAll(".chart_delete_dataset").forEach(button => {
    button.onclick = deleteDataset;
});
form.onsubmit = () => {
    let datasets = [];
    for (let i = 1; i < table.rows.length - 1; i++) {
        const row = table.rows[i];
        const values = row.cells[2].querySelector("input").value.split(";").map(value => {
            const num = parseFloat(value);
            return num.NaN ? null : num;
        })
        const dataset = {
            "label": row.cells[0].querySelector("input").value,
            "color": row.cells[1].querySelector("input").value,
            "values": values,
        };
        datasets.push(dataset);
    }
    textarea.value = JSON.stringify(datasets);
};
