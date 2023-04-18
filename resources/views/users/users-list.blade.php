@extends('layouts.main')
@section('content')
<section class="content-header">
    <h1 class="col-lg-6 no-padding">User <small>Management</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>User management</li>
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
                            <a href="{{ url(route('create-user')) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus fa-fw"></i>Create User</a>
                            @endif
                            @endisset
                        </div>
                    </div>
                    <h3 class="box-title">Users List</h3>
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
            'name': 'users-list',
            'columns': [
            {
                "name" : "name",
                "label": "Name",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 1,
                    "field" : "name"
                },
                "search": {
                    "display" : 1,
                    "type"    : "input"
                }
            },
            {
                "name" : "email",
                "label": "Email",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 1,
                    "field" : "email"
                },
                "search": {
                    "display" : 1,
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
                    "display"   : 1,
                    "field"     : "status"
                },
                "search": {
                    "display" : 1,
                    "type"    : "select",
                    'values'  : {!! json_encode($statuses) !!}
                }
            },
            {
                "name" : "date_created",
                "label": "Date Created",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 1,
                    "field" : "date_created"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
            ],
            'api_url': 'user-list',
            'data_key': 'users',
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
                            return "edit-user/"+data['uuid'];
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
                            return "view-user/"+data['uuid'];
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
                        "name"      : "Change Password",
                        "type"      : "view", //view,dialog,modal
                        "title"     : 'Change Password',
                        "url"       : function(data){
                            return "change-password/"+data['userencrypt']+'/'+data['user_role'];
                        },
                        "icon"      : "fa fa-key",
                        "method"    : "get",
                        condition: function(data) {
                            var change_password_access = "{{$change_password_access}}";
                            if (change_password_access == 1) {
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
                        "function_call" : "changeUserStatus"
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
                        "function_call" : "changeUserStatus"
                    },
                    {
                        "name"      : "Delete",
                        "type"      : "dialog", //view,dialog,modal
                        "title"     : 'Delete',
                        "url"       : function(data){
                            return "user/delete/"+data['uuid'];
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
                        "function_call" : "userDelete",
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
         * change user status
         * @param Object current object
         */
         function userDelete(current) {
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
                        userDeleteRecord(url, method);
                    }
                });
            }else {
                userDeleteRecord(url, method);
            }
        }

        /**
         * update user status
         * @param string url
         * @param string method
         */
         function userDeleteRecord(url, method) {
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
         * change user status
         * @param Object current object
         */
         function changeUserStatus(current) {
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
                        updateUserStatus(url, method);
                    }
                });
            }else {
                updateUserStatus(url, method);
            }
        }

        /**
         * update user status
         * @param string url
         * @param string method
         */
         function updateUserStatus(url, method) {
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
