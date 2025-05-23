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

const textarea = document.querySelector("textarea[name=datasets]");
textarea.parentElement.style.display = "none";
const form = textarea.form;
const table = form.querySelector("table");
table.style.display = "table";
const buttons = table.rows[table.rows.length - 1].cells[3].innerHTML;
for (let i = 1; i < table.rows.length - 1; i++) {
    table.rows[i].cells[3].innerHTML = buttons;
}
const editValuesButton = table.querySelector(".chart_edit_values");
const applyValuesButton = table.querySelector(".chart_apply_values");
for (let i = 1; i < table.rows.length - 1; i++) {
    table.rows[i].cells[2].appendChild(editValuesButton.cloneNode(true));
    table.rows[i].cells[2].appendChild(applyValuesButton.cloneNode(true));
}
const button = form.querySelector(".chart_add_dataset");
button.onclick = () => {
    const clone = table.rows[table.rows.length - 1].cloneNode(true);
    table.tBodies[0].appendChild(clone);
    clone.querySelector(".chart_edit_values").onclick = editValues;
    clone.querySelector(".chart_apply_values").onclick = applyValues;
    clone.querySelector(".chart_move_dataset").onclick = moveDataset;
    clone.querySelector(".chart_delete_dataset").onclick = deleteDataset;
}
form.querySelectorAll(".chart_edit_values").forEach(button => {
    button.onclick = editValues;
});
form.querySelectorAll(".chart_apply_values").forEach(button => {
    button.onclick = applyValues;
});
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
        const values = row.cells[2].querySelector("textarea").value.split(";").map(value => {
            const num = parseFloat(value);
            return num.NaN ? null : num;
        });
        const dataset = {
            "label": row.cells[0].querySelector("input").value,
            "color": row.cells[1].querySelector("input").value,
            "values": values,
        };
        datasets.push(dataset);
    }
    textarea.value = JSON.stringify(datasets);
};

function editValues(event) {
    const button = event.currentTarget;
    const cell = button.parentElement;
    const textarea = cell.firstElementChild;
    const values = textarea.value.split(";");
    values.forEach(value => {
        const element = document.createElement("input");
        element.type = "number";
        element.value = value;
        cell.appendChild(element);
    });
    textarea.style.display = "none";
    cell.style.display = "table-cell";
    button.style.display = "none";
    button.nextElementSibling.style.display = "";
}

function applyValues(event) {
    const button = event.currentTarget;
    const cell = button.parentElement;
    const textarea = cell.firstElementChild;
    let values = [];
    event.currentTarget.parentElement.querySelectorAll("input[type=number]").forEach(input => {
        values.push(input.value);
        input.parentElement.removeChild(input);
    });
    textarea.value = values.join(";");
    textarea.style.display = "";
    cell.style.display = "";
    button.style.display = "none";
    button.previousElementSibling.style.display = "";
}

function moveDataset(event) {
    const button = event.currentTarget;
    const cell = button.parentElement;
    const row = cell.parentElement;
    row.parentElement.insertBefore(row, row.previousElementSibling);
}

function deleteDataset(event) {
    const button = event.currentTarget;
    const cell = button.parentElement;
    const row = cell.parentElement;
    row.parentElement.removeChild(row);
}
