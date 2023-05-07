

<script src="{{ URL('js/jquery.min.js') }}"></script>
<script src="{{ URL('js/popper.min.js') }}"></script>
<script src="{{ URL('js/bootstrap.min.js') }}"></script>
<script src="{{ URL('js/bootstrap-multiselect.min.js')}}"></script>
<script src="{{ URL('js/jquery.validate.min.js') }}"></script>   

<script src="{{ URL('js/moment.min.js') }}"></script>
<script src="{{ URL('js/daterangepicker.js') }}"></script>
<script src="{{ URL('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL('js/adminlte.min.js') }}"></script>
<script src="{{ URL('js/plugins/sweet-alert/sweet_alert.js') }}"></script>
<script src="{{ URL('js/custom/config.js') }}"></script>
<script src="{{ URL('js/custom/dataTable.js') }}"></script>
<script src="{{ URL('js/custom/field_rules.js') }}"></script>
<script src="{{ URL('js/custom/formValidation.js') }}"></script>
<script src="{{ URL('js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ URL('js/moment-timezone-with-data.min.js') }}"></script>

<script type="text/javascript">
    $('input').on('keypress', function (event) {
        if($(this).hasClass('number_restrict')) {
            var regex = new RegExp("^[0-9.]+$");
        } else if($(this).hasClass('allow_characters')){
            var regex = new RegExp("^[a-zA-Z0-9!@$^&*.,-_ ]+$");
        } else if($(this).hasClass('email')){
            //var regex = new RegExp("^[a-zA-Z0-9@.");
            var regex = new RegExp("^[a-zA-Z0-9!@$^&*.,-_ ]+$");
        }
        else{
            var regex = new RegExp("^[a-zA-Z0-9' ]+$");
        }
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });
    $(window).keypress(function(e) {
        if($(e.target).hasClass('dt_search')){
            var regex = new RegExp("^[a-zA-Z0-9' ]+$");
            var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (!regex.test(key)) {
                e.preventDefault();
                return false;
            }
        }
        if(e.target.id == "date_expired_daterange_btn"){
            e.preventDefault();
            return false;
        }
    });

    $(document).ready(function () {
        $('input').bind('paste', function (e) {
            e.preventDefault();
            toastr.error('For Some Security Reasons we have blocked Paste options in this Input Field');
        });
    });

    /* Toastr Notification Start */
    if(localStorage.getItem('notification') !== null) {
        var notification = JSON.parse(localStorage.getItem('notification'));
        dataTable.showNotificationMessage(notification.type, notification.message);
        localStorage.removeItem('notification');
    }


    $('body').on('input','input[data-float]',function(e) {
        var input = parseFloat($(this).attr('data-float'));
        var regexp = (/[^\.0-9]|^0+(?=[0-9]+)|\.(?=\.|.+\.)/g);
        var regexp = (input % 1 === 0) ? integer : regexp;
        if (regexp.test(this.value)) {
            this.value = this.value.replace(regexp, '');
        }
    });
</script>
