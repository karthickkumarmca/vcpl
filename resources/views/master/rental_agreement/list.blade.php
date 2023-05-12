@extends('layouts.main')
@section('content')
<section class="content-header">
    <h1 class="col-lg-6 no-padding">Rental Agreement <small>Management</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Rental Agreement</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools">
                        <div class="btn-group">
                            @isset($create_access)
                            @if ($create_access == 1)
                            <a href="{{ url(route('create-rental-agreement')) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus fa-fw"></i>Create Rental Agreement</a>
                            @endif
                            @endisset
                        </div>
                    </div>
                    <h3 class="box-title">Rental Agreement List</h3>
                </div>
                <div class="box-body">
                    <div class="datatable_list form-inline" id="pos-custom-datatable"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('after-scripts-end')
<script type="text/javascript">

        /**
         * DataTable Properties
         */
         var table_properties = {
            'name': 'subcategories-list',
            'columns': [
             {
                "name" : "id",
                "label": "SNo",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "id"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
            {
                "name" : "tenant_name",
                "label": "Tenant Name",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "tenant_name"
                },
                "search": {
                    "display" : 1,
                    "type"    : "input"
                }
            },
            {
                "name" : "contact_person_mobile_number",
                "label": "Mobile Number",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "contact_person_mobile_number"
                },
                "search": {
                    "display" : 1,
                    "type"    : "input"
                }
            },
            {
                "name" : "rent_start_date",
                "label": "Rent Start Date",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "rent_start_date"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
             {
                "name" : "rent_end_date",
                "label": "Rent End Date",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "rent_end_date"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
            {
                "name" : "rental_amount",
                "label": "Rent Amount",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "rental_amount"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
            {
                "name" : "status",
                "label": "Status",
                "badge": {
                    "display" : 1,
                    "field"   : "status_id",
                    "color"   : {
                        0 : "bg-red",
                        1 : "bg-green"
                    }
                },
                "sort": {
                    "display"   : 0,
                    "field"     : "status"
                },
                "search": {
                    "display" : 1,
                    "type"    : "select",
                    'values'  : {!! json_encode($statuses) !!}
                }
            },
            ],
            'api_url': 'list',
            'data_key': 'records',
            'daterange_picker': {
                'display' : false,
                'default_days': 29
            },
            'action_button' : {
                'display': true,
                'action': [
                {
                    "name"      : "Edit",
                        "type"      : "view", //view,dialog,modal
                        "title"     : 'Edit',
                        "url"       : function(data){
                            return "edit/"+data['uuid'];
                        },
                        "icon"      : "fa fa-pencil",
                        "method"    : "get",
                        condition: function(data) {
                            var editaccess = "{{$edit_access}}";
                            if (editaccess == 1) {
                                return true;
                            }
                            return false;
                        }
                    },
                    {
                        "name"      : "View",
                        "type"      : "view", //view,dialog,modal
                        "title"     : 'View',
                        "url"       : function(data){
                            return "view/"+data['uuid'];
                        },
                        "icon"      : "fa fa-eye",
                        "method"    : "get",
                        condition: function(data) {
                            var viewaccess = "{{$view_access}}";
                            if (viewaccess == 1) {
                                return true;
                            }
                            return false;
                        }
                    },
                    {
                        "name"      : "Active",
                        "type"      : "dialog", //view,dialog,modal
                        "title"     : 'Change status',
                        "url"       : function(data){
                            return "update-status/"+data['uuid'];
                        },
                        "icon"      : "fa fa-check",
                        "method"    : "get",
                        "condition" : function(data){
                            var change_status_access = "{{$change_status_access}}";
                            if (change_status_access == 1) {
                                return (data['status_id'] == 0);
                            }
                            return false;
                        },
                        'confirmation' : {
                            'display' : true,
                            'title'   : "Are you sure?"
                        },
                        "function_call" : "changesubcategoriesStatus"
                    },
                    {
                        "name"      : "In-Active",
                        "type"      : "dialog", //view,dialog,modal
                        "title"     : 'Change status',
                        "url"       : function(data){
                            return "update-status/"+data['uuid'];
                        },
                        "icon"      : "fa fa-close",
                        "method"    : "get",
                        "condition" : function(data){
                            var change_status_access = "{{$change_status_access}}";
                            if (change_status_access == 1) {
                                return (data['status_id'] == 1);
                            }
                            return false;
                        },
                        'confirmation' : {
                            'display' : true,
                            'title'   : "Are you sure?"
                        },
                        "function_call" : "changesubcategoriesStatus"
                    },
                    {
                        "name"      : "Delete",
                        "type"      : "dialog", //view,dialog,modal
                        "title"     : 'Delete',
                        "url"       : function(data){
                            return "subcategories/delete/"+data['uuid'];
                        },
                        "icon"      : "fa fa-trash",
                        "method"    : "get",
                        /*"condition" : function(data){
                            return (data['status_id'] == 1);
                        },*/
                        'confirmation' : {
                            'display' : true,
                            'title'   : "Are you sure?"
                        },
                        "function_call" : "subcategoriesDelete",
                        condition: function(data) {
                            var deleteaccess = "{{$delete_access}}";
                            if (deleteaccess == 1) {
                                return true;
                            }
                            return false;
                        }
                    },

                    ]
                },
                "offset" : [
                10,
                25,
                50
                ],
                'pos_container' : 'pos-custom-datatable',
                'property_key' : 'admin_properties'
            };

        /**
         * Initiate DataTable
         */
         dataTable.initiateDatatable(table_properties);

        /**
         * change subcategories status
         * @param Object current object
         */
         function subcategoriesDelete(current) {
            var is_confirm = $(current).data('is_confirm');
            var method = $(current).data('method');
            var url = $(current).data('url');
            if(is_confirm) {
                var title = $(current).data('confirm_title');
                swal({
                    title: title,
                    text: "",
                    buttons: [
                    'CANCEL',
                    'OK'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        subcategoriesDeleteRecord(url, method);
                    }
                });
            }else {
                subcategoriesDeleteRecord(url, method);
            }
        }

        /**
         * update subcategories status
         * @param string url
         * @param string method
         */
         function subcategoriesDeleteRecord(url, method) {
            var request  = {
                'url'             : url,
                'type'            : method,
                'data'            : {},
                'dataType'        : "json",
                'success_message' : true,
                'error_message'   : true,
                'window_reload'   : true,
                'ajax_loader'     : true
            };
            dataTable.makeRequest(request, processSuspendResponse);

            function processSuspendResponse(response, status_code) {
                if(response) {
                    if(status_code == appConfig.response_code.ok) {
                        var data = response.data ? response.data : {};
                        if(data) {
                            window.location.href = data.redirect_url;
                        }
                    }
                }
            }
        }

        /**
         * change subcategories status
         * @param Object current object
         */
         function changesubcategoriesStatus(current) {
            var is_confirm = $(current).data('is_confirm');
            var method = $(current).data('method');
            var url = $(current).data('url');
            if(is_confirm) {
                var title = $(current).data('confirm_title');
                swal({
                    title: title,
                    text: "",
                    buttons: [
                    'CANCEL',
                    'OK'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        updatesubcategoriesStatus(url, method);
                    }
                });
            }else {
                updatesubcategoriesStatus(url, method);
            }
        }

        /**
         * update subcategories status
         * @param string url
         * @param string method
         */
         function updatesubcategoriesStatus(url, method) {
            var request  = {
                'url'             : url,
                'type'            : method,
                'data'            : {},
                'dataType'        : "json",
                'success_message' : true,
                'error_message'   : true,
                'window_reload'   : true,
                'ajax_loader'     : true
            };
            dataTable.makeRequest(request, processSuspendResponse);

            function processSuspendResponse(response, status_code) {
                if(response) {
                    if(status_code == appConfig.response_code.ok) {
                        var data = response.data ? response.data : {};
                        if(data) {
                            window.location.href = data.redirect_url;
                        }
                    }
                }
            }
        }

    </script>
    @stop
