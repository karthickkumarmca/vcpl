/**
 - dataTable.js
 - Custom Data Table
 - Project: Corona Admin Portal
 **/
 var dateRangeElements = [];
 var dataTableSearchName;
 let filterData = {};
 let defaultSearchValues = {};
/*window.addEventListener('load', function() {
    localStorage.setItem("search", JSON.stringify({}))
});*/
function renderOptions(options) {
    return options
    .map(option => {
        return (
            `<option
            ${option.is_selected ? 'selected="true"' : ''}
            value='${option.value}'>
            ${option.label}
            </option>`
            );
    })
    .join("");
}

function renderCheckBoxes(options, name) {
    return options.
    map(option => {
        return (
            `<label
            class="checkboxes"
            for='checkbox_${option.label}_${option.value}'>
                <input
            type="checkbox"
            class="dt_search"
            name="${name}"
            id="checkbox_${option.label}_${option.value}"
            value="${option.value}" />
            <b>&nbsp</b>
            <span>${option.label}</span>
            </label>`
            )
    })
    .join("");
}

function renderRadioButtons(options, name) {
    return options.
    map(option => {
        return (
            `<label
            class="radio_buttons"
            for='radio_${option.label}_${option.value}'>
                <input
            type="radio"
            class="dt_search"
            name="${name}"
            id="radio_${option.label}_${option.value}"
            value="${option.value}" />
            <b>&nbsp</b>
            <span>${option.label}</span>
            </label>`
            )
    })
    .join("");
}

function getInputValue(name) {
    return defaultSearchItems && defaultSearchItems[name] ? defaultSearchItems[name] : "";
}

function generateSearchField(column) {
    switch (column.input_type) {
        case "text":
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <input type="text" name="${column.key}" value="${getInputValue(column.key)}" class="dt_search form-control" />
        </div>
        </div>`;
        break;

        case "select":
        column.options.forEach(option => {
            if (option.is_selected) {
                defaultSearchValues[column.key] = option.value;
            }
        });
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <select name="${column.key}" class="dt_search form-control">
        <option value="">${
            column.placeholder
            ? column.placeholder
            : "Please select the option"
        }</option>
        ${this.renderOptions(column.options)}
        </select>
        </div>
        </div>`;

        case "checkbox":
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <br />
        ${this.renderCheckBoxes(column.options, column.key)}
        </div>
        </div>`;

        case "radio":
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <br />
        ${this.renderRadioButtons(column.options, column.key)}
        </div>
        </div>`;

        case "date":
        dateRangeElements.push({
            id: column.key + "_daterange_btn",
            options: column.daterange_picker,
            key: column.key
        });
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <input type="text" class="form-control" id="${column.key}_daterange_btn" />
        <input type="hidden" class="dt_search dt_search_start_date" name="${column.key}_start_date" value="${getInputValue(column.key)}" />
        <input type="hidden" class="dt_search dt_search_end_date" name="${column.key}_end_date" value="${getInputValue(column.key)}" />
        </div>
        </div>`;
        case "date1":
        dateRangeElements.push({
            id: column.key + "_daterange_btn",
            options: column.daterange_picker,
            key: column.key
        });
        return `<div class="col-md-6">
        <div class="form-group">
        <label>${column.label}</label>
        <input type="text" class="form-control datepicker2" id="${column.key}_daterange_btn" />
        <input type="hidden" class="dt_search dt_search_start_date1" name="${column.key}_start_date" value="${getInputValue(column.key)}" />
        <input type="hidden" class="dt_search dt_search_end_date1" name="${column.key}_end_date" value="${getInputValue(column.key)}" />
        </div>
        </div>`;
    }
}

function generateSearchColumns(columns) {
    return columns
    .map(column => {
        return generateSearchField(column);
    })
    .join("");
}

function createSearchBox(columns, dataTablePropertyKey, options) {
    let quarantineButton = '';
    if (options.quarantine_functionality) {
        quarantineButton = options.quarantine_functionality.button;
    }

    return `<h3>Search</h3>
    <div class='row'>
    ${generateSearchColumns(columns)}
    <div class="col-md-12">
    <button type="button" data-property_key='${dataTablePropertyKey}' onclick="dataTable.renderDatatableBySearch(this);" class="btn btn-success">Search</button>
    <button type="button" data-property_key='${dataTablePropertyKey}' onclick="dataTable.renderDatatableByReset(this);" class="btn btn-default">Reset</button>
    ${quarantineButton}
    </div>
    </div>`;
}

function activateDateRangePicker() {
    dateRangeElements.forEach(element => {
        var default_days = element.options && element.options.default_days ? element.options.default_days : false;
        let options = {
            autoUpdateInput: default_days ? true : false,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days")
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                moment().startOf("month"),
                moment().endOf("month")
                ],
                "Last Month": [
                moment()
                .subtract(1, "month")
                .startOf("month"),
                moment()
                .subtract(1, "month")
                .endOf("month")
                ]
            }
        };

        if (default_days) {
            var startDate     = moment().subtract(default_days, "days");
            var endDate       = moment();
            if (getInputValue(element.key + "_start_date")) {
                startDate = moment(getInputValue(element.key + "_start_date"));
            }
            if (getInputValue(element.key + "_end_date")) {
                endDate = moment(getInputValue(element.key + "_end_date"));
            }
            options.startDate = startDate;
            options.endDate   = endDate;
            $("#" + element.id + " span").html(startDate.format("MMMM D, YYYY") + " - " + endDate.format("MMMM D, YYYY"));
        }

        if($( "#" + element.id ).hasClass( "datepicker2" )){
            $("#" + element.id).daterangepicker(options);
            $("#" + element.id).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                $(".dt_search_start_date1").val(picker.startDate.format("Y-MM-DD"));
                $(".dt_search_end_date1").val(picker.endDate.format("Y-MM-DD"));
            });
        }else{
            $("#" + element.id).daterangepicker(options);
            $("#" + element.id).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                $(".dt_search_start_date").val(picker.startDate.format("Y-MM-DD"));
                $(".dt_search_end_date").val(picker.endDate.format("Y-MM-DD"));
            });
        }

    })
}

/**
 * Stores inputs that were changed their values
 * @type {Object}
 */
 let inputChangeStatus = {}

 $(document).ready(function () {
    $(document).on("change", ".dt_search", function () {
        inputChangeStatus[$(this).attr("name")] = 1;
    });
});

 function selectAllCheckBoxes(element)  {
    let dataTableCheckboxElements = $(".datatable_checkbox");
    let isSelected                = $(element).is(":checked");

    dataTableCheckboxElements.each((index, element) => {
        $(element).prop("checked", isSelected);
    });
}

function selectRow(element) {
    if ($(".datatable_checkbox").length === $(".datatable_checkbox:checked").length) {
        $("#select-all").prop("checked", true);
    }
    else {
        $("#select-all").prop("checked", false);
    }
}

var dataTable = {
    properties: {},

    initiateDatatable: function(table_properties) {
        dataTableSearchName = table_properties.name + "_search";
        defaultSearchItems  = JSON.parse(localStorage.getItem(dataTableSearchName));
        let totalItems      = localStorage.length;
        for (let index = 0; index < totalItems; index++) {
            let key = localStorage.key(index);
            if (key && (key.indexOf("_search") != -1) && dataTableSearchName !== key) {
                localStorage.removeItem(key);
            }
        }

        var property_key = table_properties["property_key"]
        ? table_properties["property_key"]
        : "table_properties";
        dataTable.properties[property_key] = table_properties;

        var datatable = "";
        if (table_properties.search) {
            datatable += createSearchBox(table_properties.search.columns, property_key, table_properties);
        }

        var table_properties = dataTable.properties[property_key];
        var name             = table_properties["name"];
        var offset           = table_properties["offset"];
        var table_name       = name + "-datatable";
        datatable            += '<div class="row" style="width:100%"><div class="col-sm-12">';

        var table_export = table_properties["export"]
        ? table_properties["export"]
        : { display: false };
        if (table_export["display"]) {
            datatable +=
            `<div class="pull-right">
            <div class="col-md-3">
            <select
            class="btn btn-success custom_datatable_print_export"
            data-property_key='${property_key}'
            data-type="export"
            onchange="dataTable.exportOrPrintDataTable(this);">
            <option value="">Export</option>
            <option value="1">Current Page</option>
            <option value="2">All Pages</option>
            </select>
            </div>
            </div>`;
        }
        var table_print = table_properties["print"]
        ? table_properties["print"]
        : { display: false };
        if (table_print["display"]) {
            datatable +=
            `<div class="pull-right">
            <div class="col-md-3">
            <select
            class="btn btn-success custom_datatable_print_export"
            data-property_key='${property_key}'
            data-type="print" onchange="dataTable.exportOrPrintDataTable(this);">
            <option value="">Print</option>
            <option value="1">Current Page</option>
            <option value="2">All Pages</option>
            </select>
            </div>
            </div>`;
        }
        datatable += `
        </div>
        </div>
        <div class="row" style="width:100%">
        <div class="col-sm-12">
        <div class="table-responsive">
        <table
        class="table table-bordered table-striped custom_datatable"
        id="${table_name}">
        <thead>
        <tr>`;

        /** Add Check box Header **/
        var th_checkbox = table_properties["checkbox"]
        ? table_properties["checkbox"]
        : { display: false };
        if (th_checkbox["display"]) {
            datatable +=
            `<th>
            <label
            class="checkboxes"
            for='select-all'>
                <input
            type="checkbox"
            class="select_all"
            name="select_all"
            id="select-all"
            onclick="selectAllCheckBoxes(this)"/>
            <b>&nbsp</b>
            </label>
            </th>`;
        }

        var columns = table_properties["columns"];
        for (c in columns) {
            var column = columns[c];
            if (column["sort"]["display"]) {
                datatable +=
                `<th
                class='sorting'
                sort='none'
                data-property_key="${property_key}"
                onclick='dataTable.sortCustomDatatable(this);'
                sort-field='${column["sort"]["field"]}'
                sort-active='0'>
                ${column["label"]}
                <i class='fa fa-sort' aria-hidden='true'></i>
                </th>`;
            }
            else {
                datatable += "<th>" + column["label"] + "</th>";
            }
        }
        var action = table_properties["action_button"];
        if (action["display"]) {
            datatable += "<th>Action</th>";
        }
        datatable += "</tr>";
        if (!table_properties.search) {
            datatable += '<tr id="' + name + '-search">';
            if (th_checkbox["display"]) {
                datatable += "<td></td>";
            }
            var th_search = table_properties["search"]
            ? table_properties["search"]
            : { display: true };
            for (c in columns) {
                var column = columns[c];

                if (th_search["display"]) {
                    if (column["search"]["display"]) {
                        var search = column["search"];
                        if (search["type"] == "input") {
                            datatable +=
                            '<td><input type="text" class="form-control dt_search" name="' +
                            column["name"] +
                            '" value="" /></td>';
                        }
                        else if (search["type"] == "select") {
                            var options = search["values"];
                            datatable += `
                            <td>
                            <select class="form-control dt_search" name="${column["name"]}">
                            <option value="">Select</option>
                            `;
                            let isSelected;
                            for (s in options) {
                                if (options[s].is_selected) {
                                    defaultSearchValues[column["name"]] = options[s]["value"];
                                }

                                isSelected = false;
                                if (!filterData[column["name"]] && options[s].is_selected) {
                                    isSelected = true;
                                }
                                if (
                                    filterData[column["name"]] && 
                                    options[s].value == filterData[column["name"]]
                                    ) {
                                    isSelected = true;
                            }

                            datatable += `
                            <option 
                            ${isSelected ? 'selected="true"' : ''}
                            value="${options[s]["value"]}">
                            ${options[s]["label"]}
                            </option>
                            `;
                        }
                        datatable += `
                        </select>
                        </td>
                        `;
                    }
                    else if (search["type"] == "date") {
                        datatable += '<td><div class="input-group">';
                        datatable +=
                        '<button type="button" class="btn btn-primary daterange-btn" id="' +
                        table_name +
                        '_daterange-btn">' +
                        "<span>" +
                        '<i class="fa fa-calendar"></i> Date range picker' +
                        "</span>" +
                        '<i class="fa fa-caret-down"></i>' +
                        "</button>";
                        datatable +=
                        '<input type="hidden" class="dt_search dt_search_start_date" name="start_date" value="" />' +
                        '<input type="hidden" class="dt_search dt_search_end_date" name="end_date" value="" />';
                        datatable += "</td></div>";
                    } else {
                        datatable += "<td></td>";
                    }
                } else {
                    datatable += "<td></td>";
                }
            }
        }
        if (th_search["display"]) {
            datatable +=
            "<td>" +
            '<button class="btn btn-sm btn-warning" type="button" data-property_key=' +
            property_key +
            ' onclick="dataTable.renderDatatableBySearch(this);">' +
            '<i class="fa fa-search" aria-hidden="true"></i>' +
            "</button> " +
            '<button class="btn btn-sm" type="button" data-property_key=' +
            property_key +
            ' onclick="dataTable.renderDatatableByReset(this);">' +
            '<i class="fa fa-times" aria-hidden="true"></i>' +
            "</button>" +
            "</td>";
        }
        datatable += "</tr>";
    }
    datatable += "</thead>";
    datatable +=
    "<tbody></tbody></table></div></div></div>";
    datatable +=
    `<div class="row w-100">
    <div id="${name}-info" class="col-sm-12 col-md-3">
    </div>
    <div class="col-sm-12 col-md-3">
    <div class="datatable_length form-inline">
    <label class="datatable_label_length">
    <span>Show </span>
    <select
    name="table_limit"
    class="form-control"
    id="${name}-tablelimit"
    data-property_key='${property_key}'
    onchange="dataTable.renderDatatableByOffset(this);">`;
    for (o in offset) {
        datatable += '<option value="' + offset[o] + '">' + offset[o] + "</option>";
    }
    datatable +=
    `           </select>
    <span> Entries</span>
    </label>
    </div>
    </div>
    <div id="${name}-pagination" class="col-sm-12 col-md-6"></div></div>`;

    var pos_container = table_properties["pos_container"]
    ? table_properties["pos_container"]
    : "pos-custom-datatable";
    $("#" + pos_container).html(datatable);
    this.renderDatatable(1, property_key);
    var daterange = table_properties["daterange_picker"]
    ? table_properties["daterange_picker"]
    : { display: false };
    if (daterange["display"]) {
        this.initiateDateRangePicker(daterange, property_key);
    }

    activateDateRangePicker();
},

getSearchFilter: function() {
    let search = {};
    let search_name, value;
    $(".dt_search").each(function() {
        search_name = $(this).attr("name");
        value       = "";

        if ($(this).attr("type") !== "checkbox" && $(this).val()) {
            value = $(this).val();
        }
        else if (($(this).attr("type") === "checkbox") && $(this).is(":checked")) {
            if (!search[search_name])  {
                value = [];
                $("input[name='" + search_name +  "']:checked").each((index, selectedValue) => {
                    value.push(selectedValue.value);
                });
            }
        }
        else if (($(this).attr("type") === "radio") && $(this).is(":checked")) {
            value = $(this).val();
            console.log(search_name, $(this).is(":checked"), value);
        }
        else if (getInputValue(search_name) && inputChangeStatus[search_name] === undefined) {
            $(this).val(getInputValue(search_name));
            value = getInputValue(search_name);
        }

            //console.log($(this).attr("type"), $(this).is(":checked"), $(this).val(), value);

            if (value.length > 0) {
                search[search_name] = value;
            }
            //console.log(search);
        });

    return search;
},

renderDatatable: function(page = 1, property_key) {
    var table_properties = dataTable.properties[property_key];
    var name             = table_properties["name"];
    var offset           = $("#" + name + "-tablelimit").val();
    var url              = table_properties["api_url"];
    var api_method       = table_properties["api_method"]
    ? table_properties["api_method"]
    : "GET";
    var data_key         = table_properties["data_key"]
    ? table_properties["data_key"]
    : "records";

    var sort = {};
    $("#" + name + "-datatable > thead > tr > th.sorting").each(function() {
        if ($(this).attr("sort-active") === "1") {
            sort["field"] = $(this).attr("sort-field");
            sort["order"] = $(this).attr("sort");
        }
    });

    var data = {
        page         : page,
        offset       : offset,
        sort         : sort,
        search       : this.getSearchFilter(),
        request_type : "json",
        data_key     : data_key,
        filter       : table_properties["filter_data"]
    };
    var request = {
        url             : url,
        type            : api_method,
        data            : data,
        dataType        : "json",
        success_message : true,
        error_message   : true,
        ajax_loader     : true,
    };

    localStorage.setItem(dataTableSearchName, JSON.stringify(data.search));
    this.makeRequest(request, renderDataTableResponse);

    function renderDataTableResponse(response, status_code) {
        if (response) {
            var records = response.data[data_key];
            var details = response.data;
            dataTable.renderTableTds(records, property_key);
            dataTable.renderTablePagination(details, property_key);
            dataTable.renderTableInfo(details, property_key);
        }
    }
},

    /**
     * Make Ajax Json request
     *
     * @param Object Request
     * @param function callback function
     * @return Object response json
     */
     makeRequest: function(request, callback) {
        if (request.ajax_loader) {
            $(".corona-preloader-backdrop").show();
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
            }
        });

        var ajaxParams = {
            type: request.type,
            url: request.url,
            data: request.data,
            dataType: request.dataType,
            success: function(response, resStatus, responseDetail) {
                if (response.message && request.success_message) {
                    if (request.window_reload) {
                        dataTable.storeMessageOnLocal(1, response.message);
                    } else {
                        dataTable.showNotificationMessage(1, response.message);
                    }
                }
                $(".corona-preloader-backdrop").hide();
                var statusCode = responseDetail.status;
                callback(response, statusCode);
            },
            error: function(error) {
                var errorResponse = eval("(" + error.responseText + ")");
                var message = "";
                if (error.status === 500) {
                    message = "Please try again later";
                } else {
                    message = errorResponse.message;
                }

                if (message != "" && request.error_message) {
                    dataTable.showNotificationMessage(2, message);
                }

                $(".corona-preloader-backdrop").hide();
                callback(errorResponse, error.status);
            }
        };

        if (request.process_data !== undefined) {
            ajaxParams.processData = false;
        }
        if (request.content_type !== undefined) {
            ajaxParams.contentType = false;
        }

        $.ajax(ajaxParams);
    },

    /**
     * Make Ajax View request
     *
     * @param Object Request
     * @param function callback function
     * @return Object Response
     */
     makeViewRequest: function(request, callback) {
        if (request.ajax_loader) {
            $("#preloader").addClass("startloader");
        }
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
            }
        });
        $.ajax({
            type: request.type,
            url: request.url,
            data: request.data ? request.data : "",
            processData: false,
            contentType: false,
            success: function(response, status, responseJSON) {
                $("#preloader").removeClass("startloader");
                var statusCode = responseJSON.status;
                callback(responseJSON, statusCode);
            },
            error: function(error) {
                var message = "";
                if (error.status === 500) {
                    message = "Unexpected error.Please try again later";
                } else {
                    message = error.responseText;
                }

                if (message != "" && request.error_message) {
                    dataTable.showNotificationMessage(2, message);
                }

                $("#preloader").removeClass("startloader");
                callback(error, error.status);
            }
        });
    },

    /**
     * Show Toastr message
     * @param integer type
     * @param string message
     */
     showNotificationMessage: function(type, message) {
        toastr.options = {
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
            fadeIn: 1000,
            fadeOut: 1000,
            timeOut: 5000,
            extendedTimeOut: 1000
        };

        if (type == 1) {
            toastr.success(message);
        } else if (type == 2) {
            toastr.error(message);
        }
    },

    /**
     * store response message on localStorage
     *
     * @param Integer type
     * @return String message
     */
     storeMessageOnLocal(type, message) {
        var notification = {
            type: type,
            message: message
        };
        localStorage.setItem("notification", JSON.stringify(notification));
    },

    /**
     * Render data table tbody values
     *
     * @param Array rows
     */
     renderTableTds: function(rows, property_key) {
        var table_properties = dataTable.properties[property_key];
        var name             = table_properties["name"];
        var tbody            = "";
        var action           = table_properties["action_button"];
        var columns          = table_properties["columns"];
        var td_checkbox      = table_properties["checkbox"]
        ? table_properties["checkbox"]
        : { display: false };

        for (i in rows) {
            action_id = "";
            tbody += "<tr>";

            if (td_checkbox["display"]) {
                var checkbox_field = td_checkbox["field"];
                var display_checkbox = true;
                if ("condition" in td_checkbox) {
                    var checkbox_condition = td_checkbox["condition"];
                    if (checkbox_condition(rows[i])) {
                        display_checkbox = true;
                    }
                    else {
                        display_checkbox = false;
                    }
                }
                if (display_checkbox) {
                    tbody +=
                    `<td>
                    <label
                    class="checkboxes"
                    for="checkbox_${rows[i][checkbox_field]}">
                        <input
                    type="checkbox"
                    class="datatable_checkbox"
                    name="${td_checkbox["name"]}"
                    value="${rows[i][checkbox_field]}"
                    onclick="selectRow(this)"
                    id="checkbox_${rows[i][checkbox_field]}" />
                    <b>&nbsp</b>
                    </label>
                    </td>`;
                }
                else {
                    tbody += "<td></td>";
                }
            }

            for (c in columns) {
                var column = columns[c];
                var c_name = column["name"];
                var c_value = rows[i][c_name];
                var c_badge = column["badge"];

                if (c_badge["display"]) {
                    var c_badge_field = c_badge["field"];
                    var c_badge_colorcodes = c_badge["color"];
                    var c_badge_value = rows[i][c_badge_field];

                    var c_badge_color = c_badge_colorcodes[c_badge_value];

                    tbody +=
                    '<td><span class="badge ' +
                    c_badge_color +
                    '">' +
                    rows[i][c_name] +
                    "</span></td>";
                }
                else if (columns[c].render) {
                    tbody += `<td>${columns[c].render(rows[i])}</td>`;
                }
                else {
                    tbody += "<td>" + rows[i][c_name] + "</td>";
                }
            }

            if (action["display"]) {
                var action_td = "<td>";
                var buttons = action["action"];
                for (b in buttons) {
                    var display = true;
                    if ("condition" in buttons[b]) {
                        var condition = buttons[b]["condition"];
                        if (condition(rows[i])) {
                            display = true;
                        } else {
                            display = false;
                        }
                    }
                    if (display) {
                        var action_url = buttons[b]["url"];
                        var act_url = action_url(rows[i]);
                        if (buttons[b]["type"] == "view") {
                            var target = buttons[b]["target"] ? buttons[b]["target"] : 0;
                            var target_html = '';
                            if(buttons[b]["target"]) {
                                target_html += "target='_blank'";
                            }
                            action_td +=
                            '<a href="' +
                            act_url +
                            '" data-toggle="tooltip" title="'+ buttons[b]['title'] +'" class="btn btn-sm btn-default" data-original-title="' +
                            buttons[b]["name"] +
                            '" '+target_html+'>' +
                            '<i class="' +
                            buttons[b]["icon"] +
                            ' text-primary"></i>' +
                            "</a>&nbsp;";
                        } else if (buttons[b]["type"] == "dialog") {
                            var confirmation = buttons[b]["confirmation"];
                            var is_confirm = confirmation["display"];
                            var title =
                            "title" in confirmation
                            ? confirmation["title"]
                            : confirmation["title"];
                            var conf_text =
                            "text" in confirmation
                            ? confirmation["text"]
                            : "";

                            action_td +=
                            '<a href="javascript:void(0);" title="'+ buttons[b]['title'] +'" data-toggle="tooltip" class="btn btn-sm btn-default" data-original-title="' +
                            buttons[b]["name"] +
                            '" data-is_confirm=' +
                            is_confirm +
                            ' data-confirm_title="' +
                            title +
                            '" data-confirm_text="' +
                            conf_text +
                            '" data-url="' +
                            act_url +
                            '" data-method=' +
                            buttons[b]["method"] +
                            ' onclick="' +
                            buttons[b]["function_call"] +
                            '(this);">' +
                            '<i class="' +
                            buttons[b]["icon"] +
                            ' text-primary"></i>' +
                            "</a>&nbsp;";
                        } else if (buttons[b]["type"] == "modal") {
                            if (act_url != undefined) {
                                action_td +=
                                `<a
                                href="javascript:void(0);"
                                data-toggle="tooltip"
                                class="btn btn-sm btn-default remove"
                                data-original-title="${buttons[b]["name"]}"
                                title="${buttons[b]["title"]}"
                                data-id='${act_url}'
                                onclick="${buttons[b]["function_call"]}(this);">
                                <i class="${buttons[b]["icon"]} text-primary"></i>
                                </a>&nbsp;`;
                            }
                        }
                    }
                }
                action_td += "</td>";
                tbody += action_td;
            }
        }

        $("#" + name + "-datatable > tbody").empty();
        $("#" + name + "-datatable > tbody").append(tbody);
    },

    /**
     * Render data table Info of displayed rows
     *
     * @param Array response
     */
     renderTableInfo: function(response, property_key) {
        var table_properties = dataTable.properties[property_key];
        var name = table_properties["name"];
        var html = "Showing 0 to 0 of 0 entries";
        var current_page = response.current_page;
        var total_items = response.total_items;
        if (total_items != 0) {
            var limit = $("#" + name + "-tablelimit").val();
            var current_items = parseInt(current_page) * parseInt(limit);
            var starting_index = parseInt(current_items - limit) + 1;
            current_items =
            current_items > total_items ? total_items : current_items;
            html =
            "Showing " +
            starting_index +
            " to " +
            current_items +
            " of " +
            total_items +
            " entries";
        }
        $("#" + name + "-info").html(html);
    },

    /**
     * Render data table pagination
     *
     * @param array response
     */
     renderTablePagination: function(response, property_key) {
        var table_properties = dataTable.properties[property_key];
        var name = table_properties["name"];
        var fixed_limit = 10;
        var current_page = response.current_page;
        var total_pages = response.total_pages;
        var total_items = response.total_items;

        var page_limit = 0;
        var prev = (next = "disabled");
        var first_onlick = (last_onclick = next_onclick = prev_onclick =
            "return false");
        var start_page = 1;
        if (total_pages != 0) {
            if (total_pages < fixed_limit) {
                page_limit = total_pages;
            } else {
                if (current_page < fixed_limit) {
                    page_limit = fixed_limit;
                } else {
                    page_limit = current_page + 4;
                    page_limit =
                    page_limit >= total_pages ? total_pages : page_limit;
                }
            }
            start_page =
            page_limit > fixed_limit ? page_limit - fixed_limit + 1 : 1;

            prev = current_page == 1 ? "disabled" : "";
            next = current_page == total_pages ? "disabled" : "";

            first_onlick =
            current_page == 1
            ? "return false"
            : "dataTable.renderDatatable(1, '" + property_key + "')";
            last_onclick =
            current_page == total_pages
            ? "return false"
            : "dataTable.renderDatatable(" +
            total_pages +
            ",'" +
            property_key +
            "')";

            next_onclick =
            current_page == total_pages
            ? "return false"
            : "dataTable.renderDatatable(" +
            (current_page + 1) +
            ",'" +
            property_key +
            "')";
            prev_onclick =
            current_page == 1
            ? "return false"
            : "dataTable.renderDatatable(" +
            (current_page - 1) +
            ",'" +
            property_key +
            "')";
        }

        var html =
        '<div class="dataTables_paginate paging_simple_numbers custom_pagination">' +
        '<ul class="pagination">';

        html +=
        '<li class="paginate_button page-item ' +
        prev +
        '">' +
        '<a class="page-link" data-property_key=' +
        property_key +
        ' onclick="' +
        first_onlick +
        '">First</a>' +
        "</li>";
        html +=
        '<li class="paginate_button page-item ' +
        prev +
        '">' +
        '<a class="page-link" onclick="' +
        prev_onclick +
        '"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>' +
        "</li>";

        for (i = start_page; i <= page_limit; i++) {
            var active = i == current_page ? "active" : "";
            var onclick = "return false";
            if (active == "") {
                onclick =
                "dataTable.renderDatatable(" +
                i +
                ",'" +
                property_key +
                "')";
            }

            html +=
            '<li class="paginate_button page-item ' +
            active +
            '">' +
            '<a class="page-link" data-property_key=' +
            property_key +
            ' onclick="' +
            onclick +
            '">' +
            i +
            "</a>" +
            "</li>";
        }
        html +=
        '<li class="paginate_button page-item ' +
        next +
        '">' +
        '<a class="page-link" data-property_key=' +
        property_key +
        ' onclick="' +
        next_onclick +
        '"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>' +
        "</li>";
        html +=
        '<li class="paginate_button page-item ' +
        next +
        '">' +
        '<a class="page-link" onclick="' +
        last_onclick +
        '">Last</a>' +
        "</li>";
        html += "</div></ul>";
        $("#" + name + "-pagination").html(html);
    },

    /**
     * Render data table sort rows
     *
     * @param Element current th
     */
     sortCustomDatatable: function(current) {
        var property_key = $(current).data("property_key");
        var table_properties = dataTable.properties[property_key];
        var name = table_properties["name"];
        var sorting = $(current).attr("sort");
        var fa_sort = "";
        var sort_active = "0";

        $("#" + name + "-datatable > thead > tr > th.sorting").each(function() {
            $(this).attr("sort", "none");
            $(this).attr("sort-active", "0");
            $(this)
            .children()
            .removeClass(
                "fa fa-sort-amount-desc fa-sort-amount-asc fa_sort_color fa-sort"
                );
            $(this)
            .children()
            .addClass("fa fa-sort");
        });

        if (sorting == "none") {
            sorting = "asc";
            fa_sort = "fa-sort-amount-asc";
            sort_active = "1";
        }
        else if (sorting == "asc") {
            sorting = "desc";
            fa_sort = "fa-sort-amount-desc";
            sort_active = "1";
        }
        else {
            sorting = "none";
            fa_sort = "fa-sort";
            sort_active = "0";
        }

        $(current).attr("sort", sorting);
        $(current).attr("sort-active", sort_active);
        $(current)
        .children()
        .removeClass(
            "fa fa-sort-amount-desc fa-sort-amount-asc fa-sort fa_sort_color"
            );
        $(current)
        .children()
        .addClass("fa " + fa_sort);

        var page = $("#" + name + "-pagination > ul > li.active > a").text();
        page = isNaN(parseInt(page)) ? 0 : page;

        this.renderDatatable(page, property_key);
    },

    renderDatatableByOffset: function(current) {
        var property_key = $(current).data("property_key");
        this.renderDatatable(1, property_key);
    },

    /**
     * Render Datatable when we click on search button
     *
     * @param object current html dom object
     */
     renderDatatableBySearch: function(current) {
        var property_key = $(current).data("property_key");
        this.renderDatatable(1, property_key);
    },

    /**
     * Render Datatable when we click on reset button
     *
     * @param object current html dom object
     */
     renderDatatableByReset: function(current) {
        localStorage.setItem(dataTableSearchName, "{}");
        defaultSearchItems = {};
        $(".dt_search").each(function(index, element) {
            if ($(element).attr("type") === "checkbox") {
                $(element).prop("checked", false);
            }
            else {
                $(this).val("");
            }
        });
        $("#date_expired_daterange_btn").val('');
        var property_key = $(current).data("property_key");
        this.resetDatatable(property_key);
    },

    /**
     * Reset data table
     *
     */
     resetDatatable: function(property_key) {
        var table_properties = dataTable.properties[property_key];
        var name = table_properties["name"];
        var table_name = name + "-datatable";
        $("#" + name + "-search > td > input.dt_search").val("");
        $("#" + name + "-search > td > select.dt_search").val("");
        $(".dt_search").val("");
        var daterange = table_properties["daterange_picker"]
        ? table_properties["daterange_picker"]
        : { display: false };
        if (daterange["display"]) {
            this.initiateDateRangePicker(daterange, property_key);
        }
        this.renderDatatable(1, property_key);
    },

    /**
     * Initiate Date range picker
     * @param array daterange
     * @param string table_name
     */
     initiateDateRangePicker: function(daterange, property_key) {
        var table_properties = dataTable.properties[property_key];
        var name = table_properties["name"];
        var table_name = name + "-datatable";
        var default_days = daterange["default_days"];
        var startDate = moment().subtract(default_days, "days");
        var endDate = moment();
        if (default_days) {
            $("#" + table_name + "_daterange-btn span").html(
                startDate.format("MMMM D, YYYY") +
                " - " +
                endDate.format("MMMM D, YYYY")
                );
        }
        $("#" + table_name + "_daterange-btn").daterangepicker(
        {
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days")
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                moment().startOf("month"),
                moment().endOf("month")
                ],
                "Last Month": [
                moment()
                .subtract(1, "month")
                .startOf("month"),
                moment()
                .subtract(1, "month")
                .endOf("month")
                ]
            },
            startDate: startDate,
            endDate: endDate
        },
        function(start, end) {
            $("#" + table_name + "_daterange-btn span").html(
                start.format("MMMM D, YYYY") +
                " - " +
                end.format("MMMM D, YYYY")
                );
            var start_date = start.format("Y-MM-DD");
            var end_date = end.format("Y-MM-DD");
            $(".dt_search_start_date").val(start_date);
            $(".dt_search_end_date").val(end_date);
        }
        );
    },
    /**
     * Export Or Print the datatable
     *
     * @param object current html dom object
     */
     exportOrPrintDataTable: function(current) {
        var property_key = $(current).data("property_key");
        var option = $(current).val();
        if (option != "") {
            var table_properties = dataTable.properties[property_key];
            var name = table_properties["name"];
            var offset = $("#" + name + "-tablelimit").val();

            var sort = {};
            $("#" + name + "-datatable > thead > tr > th.sorting").each(
                function() {
                    if ($(this).attr("sort-active") === "1") {
                        sort["field"] = $(this).attr("sort-field");
                        sort["order"] = $(this).attr("sort");
                    }
                }
                );

            var headers = [];
            var columns = table_properties["columns"];
            for (c in columns) {
                var column = columns[c];
                headers.push(column["label"]);
            }

            var page = $(
                "#" + name + "-pagination .dataTables_paginate .pagination"
                )
            .find("li.active")
            .find("a")
            .html();
            if (page != undefined) {
                var type = $(current).data("type");
                if (type == "export") {
                    var table_export = table_properties["export"];
                    var export_api_url = table_export["api_url"];
                    window.location.href =
                    export_api_url +
                    "?page=" +
                    page +
                    "&offset=" +
                    offset +
                    "&sort=" +
                    JSON.stringify(sort) +
                    "&search=" +
                    JSON.stringify(this.getSearchFilter()) +
                    "&export_option=" +
                    option +
                    "&columns=" +
                    JSON.stringify(headers) +
                    "";
                } else {
                    var table_print = table_properties["print"];
                    var api_method = table_print["api_method"];
                    var data = {
                        page: page,
                        offset: offset,
                        sort: sort,
                        search: this.getSearchFilter(),
                        export_option: option,
                        columns: headers
                    };
                    var print_api_url = table_print["api_url"];
                    var request = {
                        url: print_api_url,
                        type: api_method,
                        data: data,
                        dataType: "json",
                        success_message: false,
                        error_message: true,
                        ajax_loader: true
                    };
                    this.makeRequest(request, renderPrintDataTableResponse);
                    function renderPrintDataTableResponse(
                        response,
                        status_code
                        ) {
                        if (response) {
                            if (status_code == posConfig.response_code.ok) {
                                var data = response.data;
                                var newWin = window.open("", "Print-Window");
                                newWin.document.open();
                                newWin.document.write(
                                    '<html><body onload="window.print()">' +
                                    data +
                                    "</body></html>"
                                    );
                                newWin.document.close();
                                setTimeout(function() {
                                    newWin.close();
                                }, 10);
                                window.onfocus = function() {
                                    setTimeout(function() {
                                        newWin.close();
                                    }, 10);
                                };
                                window.onmousemove = function() {
                                    setTimeout(function() {
                                        newWin.close();
                                    }, 10);
                                };
                            }
                        }
                    }
                }
                $(current).val("");
            } else {
                dataTable.showNotificationMessage(2, "No records found");
                $(current).val("");
            }
        }
    }
};

$(document).on("click", ".dt_select_checkbox", function() {
    var check_type = $(this).data("check_type");
    if (check_type == "single") {
        $(".dt_select_checkbox")
        .not(this)
        .prop("checked", false);
    }
});

$('.nav-link').on('click',function () {
    $('.nav-link.active.show').css('border-top-color','transparent');
    setTimeout(function () {
        $('.nav-link.active.show').css('border-top-width','3px');
        $('.nav-link.active.show').css('border-top-color','#3c8dbc');
    },100);
})
