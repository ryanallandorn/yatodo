import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import DateTime from 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import 'datatables.net-rowreorder-bs5';
import 'datatables.net-scroller-bs5';
import 'datatables.net-searchbuilder-bs5';
import 'datatables.net-searchpanes-bs5';
import 'datatables.net-select-bs5';
import 'datatables.net-staterestore-bs5';

const defaultDataTableOptions = {
    processing: true,
    serverSide: true,
    responsive: true,
    buttons: [
        {
            text: '<i class="bi bi-arrow-clockwise"></i>',
            action: function (e, dt, node, config) {
                dt.ajax.reload();
            }
        }
    ],
    dom: '<"d-flex dt-toolbar-top justify-content-between pt-3 pb-3"<"d-flex justify-content-start"<"dt-length"l><"dt-buttons"B>><"d-flex justify-content-end"f>>t<"d-flex dt-toolbar-bottom justify-content-between pt-3 pb-3"<"d-flex justify-content-start"i><"d-flex justify-content-end"p>>',
    language: {
        lengthMenu: "_MENU_"
    },
    columns: []
};

/**
 * Sanitize string to prevent XSS attacks
 */
function sanitizeString(str) {
    return str.replace(/[^a-z0-9áéíóúñü \.,_-]/gim, "").trim();
}


/**
 *
 */
const formatterDefault = function (data, type, row, meta) {
    return data;
};


/**
 *
 */
const formatterCollapseRow = function (data, type, row, meta) {
    // Check if the row has a child row class
    let hasChildClass = row && row.className && row.className.includes('dt-hasChild');

    // Return the icon with any additional custom rendering logic
    return `<i class="bi collapse-icon"></i> ${data}`;
};


/**
 *
 */
const myFunction = function (data, type, row, meta) {
    return `Rendered: ${data}`;
};


/**
 *
 */
const formatterFunctions = {
    'formatterDefault': formatterDefault,
    'formatterCollapseRow': formatterCollapseRow,
};

/**
 * Build columns array from table headers
 */
const buildColumnsArray = (table) => {
    let columnsArray = [];
    let headers = table.querySelectorAll('th');
    headers.forEach(th => {
        let columnName = sanitizeString(th.dataset.column);
        let columnClass = sanitizeString(th.className || '');
        let sortable = th.dataset.sortable !== 'false';
        let renderFunctionName = th.dataset.formatter; // Get the function name as a string
        let renderFunction = renderFunctionName && formatterFunctions[renderFunctionName]
            ? formatterFunctions[renderFunctionName]
            : formatterDefault;

        if (columnName) {
            let columnDefinition = {
                data: columnName,
                className: `${columnClass} col-${columnName}`,
                orderable: sortable
            };
            
            if (renderFunction) {
                columnDefinition.render = renderFunction;
            }

            columnsArray.push(columnDefinition);
        }
    });
    return columnsArray;
}




/**
 * Format child data for DataTable
 */
function formatChildData(d) {
    let details = '<dl>';
    for (let key in d) {
        if (d.hasOwnProperty(key)) {
            details += `<dt>${key}:</dt><dd>${d[key]}</dd>`;
        }
    }
    details += '</dl>';
    return details;
}

/**
 * Initialize DataTable
 */
export function initDataTable() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let tables = document.querySelectorAll('table.dT');
    tables.forEach(table => {
        let endpoint = table.dataset.endpoint;
        let filters = JSON.parse(table.dataset.filters); // Parse the filters

        let columnsArray = buildColumnsArray(table);

        let options = {
            ...defaultDataTableOptions,
            ajax: {
                url: endpoint,
                type: 'POST',
                data: function(d) {
                    // Append filters to the request data
                    return {
                        ...d,
                        filters: filters
                    };
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            },
            columns: columnsArray,
            rowCallback: function(row, data, index) {
                // if (data.validations) {
                //     row.querySelector('.col-validationIssues').classList.add('validation-issues');
                //     for (let columnName in data.validations) {
                //         if (data.validations.hasOwnProperty(columnName)) {
                //             let cellIndex = columnsArray.findIndex(column => column.data === columnName);
                //             if (cellIndex !== -1) {
                //                 let cell = row.querySelectorAll('td')[cellIndex];
                //                 cell.innerHTML = data.validations[columnName];
                //                 cell.classList.add('validation-issues');
                //                 row.classList.add('validation-issues');
                //             }
                //         }
                //     }
                // }
            }
        };

        let dataTable = new DataTable(table, options);

        // Add event listener for opening and closing details
        table.addEventListener('click', function(e) {
            if (e.target.closest('td.dt-collapse-row')) {
                let tr = e.target.closest('tr');
                let row = dataTable.row(tr);

                //
                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    row.child(formatChildData(row.data())).show();
                }
            }
        });

    });
}


/**
 * Select all checkboxes in a table
 */
document.addEventListener("change", function(e) {
    if (e.target.id === "select-all") {
        let table = e.target.closest('table');
        let rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }
});



document.addEventListener("DOMContentLoaded", function() {
    initDataTable();
});