$('.tab1').click(function(){
    $('#selected_tab').val(2);
});
$('.tab2').click(function(){
    $('#selected_tab').val(1);
});

$("#admin-form").validate({
        rules: {
            
            bill_number: {
                required: true,
                normalizer:function( value ) {
                    return $.trim(value);
                },
            },
            rquantity: {
                required: true,
                normalizer:function( value ) {
                    return $.trim(value);
                },
            },
            grand_and_brand: {
                required: true,
                normalizer:function( value ) {
                    return $.trim(value);
                },
            },
            transfer_slip_number: {
                required: true,
                normalizer:function( value ) {
                    return $.trim(value);
                },
            },
            vechile_number: {
                required: true
            },
            vehicle_id: {
                required: true
            },
            site_id: {
                required: true
            },
            quantity: {
                required: true,
                normalizer:function( value ) {
                    return $.trim(value);
                },
            },
            
        },
       
        submitHandler: function(form) {
            // console.log(form);
            form.submit();
        }
    });