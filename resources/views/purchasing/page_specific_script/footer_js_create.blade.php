    <script src="{{ asset ("/assets/jquery/js/jquery-3.6.0.min.js") }}"></script>
    <script src="{{ asset ("/assets/node_modules/@popperjs/core/dist/umd/popper.min.js") }}"></script>
    <script src="{{ asset ("/assets/node_modules/gijgo/js/gijgo.min.js") }}"></script>
    <script src="{{ asset ("/assets/node_modules/jquery-toast-plugin/dist/jquery.toast.min.js") }}"></script>
    <script src="{{ asset ("/assets/bootstrap/dist/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset ("/assets/bower_components/jquery-validation/dist/jquery.validate.min.js") }}"></script>
    <script src="{{ asset ("/assets/bower_components/select2/dist/js/select2.full.min.js") }}"></script>
    <script src="{{ asset ("/assets/datatables/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset ("/assets/datatables/js/dataTables.bootstrap4.min.js") }}"></script>
    <script src="{{ asset ("/assets/datatables/js/dataTables.rowReorder.min.js") }}"></script>
    <script src="{{ asset ("/assets/datatables/js/dataTables.buttons.min.js") }}"></script>
    <script src="{{ asset ("/assets/cto/js/cakrudtemplate.js") }}"></script>
    <script src="{{ asset ("/assets/cto/js/cto_loadinganimation.min.js") }}"></script>
    <script src="{{ asset ("/assets/cto/js/dateformatvalidation.min.js") }}"></script>
<script>
    var editor;

$(function () {
    var dt = new Date();
    var tgl = (dt.getDate()<10?"0"+dt.getDate():dt.getDate())+"/"+((dt.getMonth()+1)<10?"0"+(dt.getMonth()+1):(dt.getMonth()+1))+"/"+dt.getFullYear()+" "+(dt.getHours()<10?"0"+dt.getHours():dt.getHours())+":"+(dt.getMinutes()<10?"0":dt.getMinutes())
    @if($page_data["page_method_name"] != "View")
    $("#purchasing_datetime").datetimepicker({
        format:"dd/mm/yyyy HH:MM",
        modal: true,
        footer: true,
        uiLibrary: 'bootstrap'@if($page_data["page_method_name"] == "Create"),
        value: tgl @endif
    });
    @endif
    @if($page_data["page_method_name"] != "View")
    $("#buying_datetime").datetimepicker({
        format:"dd/mm/yyyy HH:MM",
        modal: true,
        footer: true,
        uiLibrary: 'bootstrap'@if($page_data["page_method_name"] == "Create"),
        value: tgl @endif
    });
    @endif

    $.validator.setDefaults({
        submitHandler: function (form, event) {
            event.preventDefault();
            cto_loading_show();
            var quickForm = $("#quickForm");
            var ctct1_purchasing_detail = [];
            var table = $("#ctct1_purchasing_detail").DataTable().rows().data();
            for(var i = 0; i < table.length; i++){
                ctct1_purchasing_detail.push({"no_seq": table[i][0], "product": table[i][1], "product_label": table[i][2], "purchasing_price": table[i][3], "quantity": table[i][4], "discount_percentage": table[i][5], "discount_total": table[i][6], "total": table[i][7], "warehouse": table[i][8], "warehouse_label": table[i][9], "id": table[i][table.columns().header().length-1]});
            }
            $("#ct1_purchasing_detail").val(JSON.stringify(ctct1_purchasing_detail));

            var ctct2_payment_detail = [];
            var table = $("#ctct2_payment_detail").DataTable().rows().data();
            for(var i = 0; i < table.length; i++){
                ctct2_payment_detail.push({"no_seq": table[i][0], "payment_type": table[i][1], "paying_method": table[i][2], "paying_method_label": table[i][3], "paying": table[i][4], "payment_notes": table[i][5], "id": table[i][table.columns().header().length-1]});
            }
            $("#ct2_payment_detail").val(JSON.stringify(ctct2_payment_detail));

            var id_{{$page_data["page_data_urlname"]}} = 0;
            var values = $("#quickForm").serialize();

            var values = $('#quickForm').serialize();
            var ajaxRequest;
            ajaxRequest = $.ajax({
                @if($page_data["page_method_name"] == "Update")
                url: "/update{{$page_data["page_data_urlname"]}}/{{$page_data["id"]}}",
                @else
                url: "/store{{$page_data["page_data_urlname"]}}",
                @endif
                type: "post",
                data: values,
                success: function(data){
                    if(data.status >= 200 && data.status <= 299){
                        id_{{$page_data["page_data_urlname"]}} = data.data.id;
                            $.toast({
                                text: data.message,
                                heading: 'Status',
                                icon: 'success',
                                showHideTransition: 'fade',
                                allowToastClose: true,
                                hideAfter: 3000,
                                position: 'mid-center',
                                textAlign: 'left'
                            });
                    }
                    cto_loading_hide();
                    @if($page_data["page_method_name"] == "Update")
                    getdata();
                    @endif
                },
                error: function (err) {
                    if (err.status == 422) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            var validator = $("#quickForm").validate();
                            var errors = {};
                            if(i == "product" || i == "product_label" || i == "purchasing_price" || i == "quantity" || i == "discount_percentage" || i == "discount_total" || i == "total" || i == "warehouse" || i == "warehouse_label"){
                                errors["ct1_purchasing_detail"] = error[0];
                            }else{
                                errors[i] = error[0];
                            }
                            validator.showErrors(errors);
                    });
                }
                cto_loading_hide();
            }
        });
    }
});


$("select").select2({
    allowClear: true,
    placeholder: "Pilih satu!",
    theme: "bootstrap4" @if($page_data["page_method_name"] == "View"),
    disabled: true @endif
}); 

$.fn.modal.Constructor.prototype._enforceFocus = function() {

};

$("#supplier").on("change", function() {
    $("#supplier_label").val($("#supplier option:selected").text());
});

$("#product").on("change", function() {
    $("#product_label").val($("#product option:selected").text());
    setPurchasingPrice($("#product").val());
    setAvailableStock();
});

$("#warehouse").on("change", function() {
    $("#warehouse_label").val($("#warehouse option:selected").text());
    setAvailableStock();
});

$("#purchasing_detail_total").on("change", function() {
    var purchasing_detail_total = $("#purchasing_detail_total").val();
    var purchasing_discount_percentage = $("#purchasing_discount_percentage").val();
    $("#purchasing_discount_total").val(purchasing_detail_total*purchasing_discount_percentage/100);
    var purchasing_discount_total = $("#purchasing_discount_total").val();
    $("#purchasing_total").val(purchasing_detail_total-purchasing_discount_total);
});

$("#purchasing_discount_percentage").on("change", function() {
    var purchasing_detail_total = $("#purchasing_detail_total").val();
    var purchasing_discount_percentage = $("#purchasing_discount_percentage").val();
    $("#purchasing_discount_total").val(purchasing_detail_total*purchasing_discount_percentage/100);
    var purchasing_discount_total = $("#purchasing_discount_total").val();
    $("#purchasing_total").val(purchasing_detail_total-purchasing_discount_total);
});

$("#purchasing_discount_total").on("change", function() {
    var purchasing_detail_total = $("#purchasing_detail_total").val();
    var purchasing_discount_total = $("#purchasing_discount_total").val();
    $("#purchasing_discount_percentage").val(purchasing_discount_total*100/purchasing_detail_total);
    var purchasing_discount_total = $("#purchasing_discount_total").val();
    $("#purchasing_total").val(purchasing_detail_total-purchasing_discount_total);
});

$("#quantity").on("change", function() {
    var quantity = $("#quantity").val();
    var purchasing_price = $("#purchasing_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(purchasing_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((purchasing_price-discount_total)*quantity);
});

$("#purchasing_price").on("change", function() {
    var quantity = $("#quantity").val();
    var purchasing_price = $("#purchasing_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(purchasing_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((purchasing_price-discount_total)*quantity);
    $("#buying_total").trigger("change");
});

$("#discount_percentage").on("change", function() {
    var quantity = $("#quantity").val();
    var purchasing_price = $("#purchasing_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(purchasing_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((purchasing_price-discount_total)*quantity);
    $("#buying_total").trigger("change");
});

$("#discount_total").on("change", function() {
    var quantity = $("#quantity").val();
    var purchasing_price = $("#purchasing_price").val();
    var discount_total = $("#discount_total").val();
    $("#discount_percentage").val(discount_total*100/purchasing_price);
    var discount_total = $("#discount_total").val();
    $("#total").val((purchasing_price-discount_total)*quantity);
    $("#buying_total").trigger("change");
});

$("#paying_method").on("change", function() {
    $("#paying_method_label").val($("#paying_method option:selected").text());
});

$("#buying_total").on("change", function() {
    var purchasing_total = $("#purchasing_total").val();
    var buying_total = $("#buying_total").val();
    $("#change_total").val(buying_total-purchasing_total);
});

var fields = $("#quickForm").serialize();

$.ajax({
    url: "/getoptions{{$page_data["page_data_urlname"]}}",
    type: "post",
    data: {
        fieldname: "paying_method",
        _token: $("#quickForm input[name=_token]").val()
    },
    success: function(data){
        var newState = new Option("", "", true, false);
        $("#paying_method").append(newState).trigger("change");
        for(var i = 0; i < data.length; i++){
            if(data[i].name){
                newState = new Option(data[i].label, data[i].name, true, false);
                $("#paying_method").append(newState).trigger("change");
            }
        }
    },
    error: function (err) {
        if (err.status == 422) {
            $.each(err.responseJSON.errors, function (i, error) {
                var validator = $("#quickForm").validate();
                var errors = {}
                errors[i] = error[0];
                validator.showErrors(errors);
            });
        }
    }
});

$("#supplier").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "supplier",
                _token: $("input[name=_token]").val()
            }
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: data.items,
                pagination: {
                more: (params.page * 25) < data.total_count
                }
            };
        },
        cache: true
    }
});

$("#product").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "product",
                _token: $("input[name=_token]").val()
            }
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: data.items,
                pagination: {
                more: (params.page * 25) < data.total_count
                }
            };
        },
        cache: true
    }
});

$("#warehouse").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "warehouse",
                _token: $("input[name=_token]").val()
            }
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: data.items,
                pagination: {
                more: (params.page * 25) < data.total_count
                }
            };
        },
        cache: true
    }
});

$("#quickForm").validate({
    rules: {
        purchasing_name :{
            minlength:2,
            maxlength:255
        },
        purchasing_datetime :{
            required: true
        },
        buying_datetime :{
            required: true
        },
        purchasing_detail_total :{
            required: true,
            number: true
        },
        purchasing_discount_percentage :{
            required: true,
            number: true,
            max:100
        },
        purchasing_discount_total :{
            required: true,
            number: true
        },
        purchasing_total :{
            required: true,
            number: true
        },
        buying_total :{
            required: true,
            number: true
        },
        change_total :{
            required: true,
            number: true
        },
    },
    messages: {
        purchasing_name :{
            minlength: "Kode Penjualan minimal 2 karakter!!",
            maxlength: "Kode Penjualan maksimal 255 karakter!!"
        },
        purchasing_datetime :{
            required: "Waktu Penjualan harus diisi!!"
        },
        buying_datetime :{
            required: "Waktu Pembayaran harus diisi!!"
        },
        purchasing_detail_total :{
            required: "Total Detail Penjualan harus diisi!!",
            number: "Total Detail Penjualan harus berupa angka!!"
        },
        purchasing_discount_percentage :{
            required: "Persen Diskon Penjualan harus diisi!!",
            number: "Persen Diskon Penjualan harus berupa angka!!",
            max: "Persen Diskon Penjualan maksimal 100!!"
        },
        purchasing_discount_total :{
            required: "Nominal Diskon Penjualan harus diisi!!",
            number: "Nominal Diskon Penjualan harus berupa angka!!"
        },
        purchasing_total :{
            required: "Total Penjualan harus diisi!!",
            number: "Total Penjualan harus berupa angka!!"
        },
        buying_total :{
            required: "Total Pembayaran harus diisi!!",
            number: "Total Pembayaran harus berupa angka!!"
        },
        change_total :{
            required: "Total Kembalian harus diisi!!",
            number: "Total Kembalian harus berupa angka!!"
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".col-sm-6").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
    });

$("#quickModalForm_ct1_purchasing_detail").validate({
    rules: {
        product :{
            required: true
        },
        purchasing_price :{
            required: true,
            number: true
        },
        quantity :{
            required: true,
            number: true
        },
        discount_percentage :{
            number: true,
            max:100
        },
        discount_total :{
            number: true
        },
        total :{
            required: true,
            number: true
        },
        warehouse :{
            required: true
        },
    },
    messages: {
        product :{
            required: "Produk harus diisi!!"
        },
        purchasing_price :{
            required: "Harga Jual harus diisi!!",
            number: "Harga Jual harus berupa angka!!"
        },
        quantity :{
            required: "Kuantitas harus diisi!!",
            number: "Kuantitas harus berupa angka!!"
        },
        discount_percentage :{
            number: "Persen Diskon harus berupa angka!!",
            max: "Persen Diskon maksimal 100!!"
        },
        discount_total :{
            number: "Nominal Diskon harus berupa angka!!"
        },
        total :{
            required: "Total harus diisi!!",
            number: "Total harus berupa angka!!"
        },
        warehouse :{
            required: "Gudang harus diisi!!"
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".col-sm-6").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
    });

    $("#quickModalForm_ct2_payment_detail").validate({
    rules: {
        payment_type :{
            required: true
        },
        paying_method :{
            required: true
        },
        paying :{
            required: true,
            number: true
        },
    },
    messages: {
        payment_type :{
            required: "Tipe Pembayaran harus diisi!!"
        },
        paying_method :{
            required: "Metode Pembayaran harus diisi!!"
        },
        paying :{
            required: "Nominal Bayar harus diisi!!",
            number: "Nominal Bayar harus berupa angka!!"
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".col-sm-6").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
    });

});
$(document).ready(function() {
    var table_ct1_purchasing_detail = $("#ctct1_purchasing_detail").DataTable({
        @if($page_data["page_method_name"] != "View")
        rowReorder: true,
        @endif
        //add button
        dom: "Bfrtip" @if($page_data["page_method_name"] != "View") ,
        buttons: [
            {
                text: "New",
                action: function ( e, dt, node, config ) {
                    $("#staticBackdrop_ct1_purchasing_detail").modal({"show": true});
                    addChildTable_ct1_purchasing_detail("staticBackdrop_ct1_purchasing_detail");
                }
            }
        ]
        @endif
    });

    table_ct1_purchasing_detail.column(table_ct1_purchasing_detail.columns().header().length-1).visible(false);
    table_ct1_purchasing_detail.column(1).visible(false);
    table_ct1_purchasing_detail.column(5).visible(false);
    table_ct1_purchasing_detail.column(6).visible(false);
    table_ct1_purchasing_detail.column(8).visible(false);
    table_ct1_purchasing_detail.column(9).visible(false);
    // table_ct1_purchasing_detail.column(3).visible(false);
    // table_ct1_purchasing_detail.column(4).visible(false);
    // table_ct1_purchasing_detail.column(5).visible(false);
    // table_ct1_purchasing_detail.column(6).visible(false);
    // table_ct1_purchasing_detail.column(7).visible(false);
    // table_ct1_purchasing_detail.column(10).visible(false);
    // table_ct1_purchasing_detail.column(11).visible(false);
    // table_ct1_purchasing_detail.column(13).visible(false);
    // table_ct1_purchasing_detail.column(14).visible(false);

    $("#ctct1_purchasing_detail tbody").on( "click", ".row-show", function () {
        $("#staticBackdrop_ct1_purchasing_detail").modal({"show": true});
        showChildTable_ct1_purchasing_detail("staticBackdrop_ct1_purchasing_detail", table_ct1_purchasing_detail.row( $(this).parents("tr") ));
    } );

    $("#staticBackdropClose_ct1_purchasing_detail").click(function(){
        $("#staticBackdrop_ct1_purchasing_detail").modal("hide");
    });

    table_ct1_purchasing_detail.on( "row-reorder", function ( e, diff, edit ) {
            var result = "Reorder started on row: "+edit.triggerRow.data()[1]+"<br>";
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table_ct1_purchasing_detail.row( diff[i].node ).data();
                result += rowData[1]+" updated to be in position "+
                    diff[i].newData+" (was "+diff[i].oldData+")<br>";
            }
        $("#result").html( "Event result:<br>"+result );
    } );
    $("#ctct1_purchasing_detail tbody").on("click", ".row-delete", function () {
        table_ct1_purchasing_detail.row($(this).parents("tr")).remove().draw();
    });

    var table_ct2_payment_detail = $("#ctct2_payment_detail").DataTable({
        @if($page_data["page_method_name"] != "View")
        rowReorder: true,
        @endif
        //add button
        dom: "Bfrtip" @if($page_data["page_method_name"] != "View") ,
        buttons: [
            {
                text: "New",
                action: function ( e, dt, node, config ) {
                    $("#staticBackdrop_ct2_payment_detail").modal({"show": true});
                    addChildTable_ct2_payment_detail("staticBackdrop_ct2_payment_detail");
                }
            }
        ]
        @endif
    });

    table_ct2_payment_detail.column(table_ct2_payment_detail.columns().header().length-1).visible(false);
    table_ct2_payment_detail.column(1).visible(false);
    table_ct2_payment_detail.column(2).visible(false);

    $("#ctct2_payment_detail tbody").on( "click", ".row-show", function () {
        $("#staticBackdrop_ct2_payment_detail").modal({"show": true});
        showChildTable_ct2_payment_detail("staticBackdrop_ct2_payment_detail", table_ct2_payment_detail.row( $(this).parents("tr") ));
    } );

    $("#staticBackdropClose_ct2_payment_detail").click(function(){
        $("#staticBackdrop_ct2_payment_detail").modal("hide");
    });

    table_ct2_payment_detail.on( "row-reorder", function ( e, diff, edit ) {
            var result = "Reorder started on row: "+edit.triggerRow.data()[1]+"<br>";
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table_ct2_payment_detail.row( diff[i].node ).data();
                result += rowData[1]+" updated to be in position "+
                    diff[i].newData+" (was "+diff[i].oldData+")<br>";
            }
        $("#result").html( "Event result:<br>"+result );
    } );
    $("#ctct2_payment_detail tbody").on("click", ".row-delete", function () {
        table_ct2_payment_detail.row($(this).parents("tr")).remove().draw();
    });

    $("#is_paynow").on("change", function () {
        if($("#is_paynow").is(":checked")){
            $("#fg_buying_total").removeClass("d-none");
            $("#fg_change_total").removeClass("d-none");
            $("#fg_buying_datetime").removeClass("d-none");
            $("#fg_ct2_payment_detail").removeClass("d-none");
        }else{
            $("#fg_buying_total").addClass("d-none");
            $("#fg_change_total").addClass("d-none");
            $("#fg_buying_datetime").addClass("d-none");
            $("#fg_ct2_payment_detail").addClass("d-none");
        }
    });

    @if($page_data["page_method_name"] == "Create") 
    $("input[name=is_paynow").prop("checked", "on").trigger("change");
    @endif

    @if($page_data["page_method_name"] == "Update" || $page_data["page_method_name"] == "View")
    getdata();
    @endif
} );

@if($page_data["page_method_name"] == "Update" || $page_data["page_method_name"] == "View")
function getdata(){
    cto_loading_show();
    $.ajax({
        url: "/getdata{{$page_data["page_data_urlname"]}}",
        type: "post",
        data: {
            id: {{$page_data["id"]}},
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            for(var i = 0; i < Object.keys(data.data.{{$page_data["page_data_urlname"]}}).length; i++){
                if(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i] == "supplier"){
                    if(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]){
                        var newState = new Option(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"_label"], data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]], true, false);
                        $("#"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]).append(newState).trigger("change");
                    }
                }else{
                    if(["is_paynow"].includes(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i])){
                        $("input[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").prop("checked", data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                    }else{
                        $("input[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").val(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                        $("textarea[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").val(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                        if(["ewfsdfsafdsafasdfasdferad"].includes(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i])){
                            if(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]] != null){
                                $("#btn_"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"").removeAttr("disabled");
                                $("#btn_"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"").addClass("btn-success text-white");
                                $("#btn_"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"").removeClass("btn-primary");
                                var filename = Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i];
                                $("label[for=upload_"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").html(filename);
                                $("#btn_"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"").html("Download");
                            }
                        }
                    }
                    $("select[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").val(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]).change();
                }
                }

            $("#ctct1_purchasing_detail").DataTable().clear().draw();
            if(data.data.ct1_purchasing_detail.length > 0){
                for(var i = 0; i < data.data.ct1_purchasing_detail.length; i++){
                    var dttb = $('#ctct1_purchasing_detail').DataTable();
                    var child_table_data = [data.data.ct1_purchasing_detail[i].no_seq, data.data.ct1_purchasing_detail[i].product, data.data.ct1_purchasing_detail[i].product_label, data.data.ct1_purchasing_detail[i].purchasing_price, data.data.ct1_purchasing_detail[i].quantity, data.data.ct1_purchasing_detail[i].discount_percentage, data.data.ct1_purchasing_detail[i].discount_total, data.data.ct1_purchasing_detail[i].total, data.data.ct1_purchasing_detail[i].warehouse, data.data.ct1_purchasing_detail[i].warehouse_label, @if($page_data["page_method_name"] != "View") '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>' @else '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>' @endif, data.data.ct1_purchasing_detail[i].id];
                    if(dttb.row.add(child_table_data).draw( false )){

                    }
                }
            }

            $("#ctct2_payment_detail").DataTable().clear().draw();
            if(data.data.ct2_payment_detail.length > 0){
                for(var i = 0; i < data.data.ct2_payment_detail.length; i++){
                    var dttb = $('#ctct2_payment_detail').DataTable();
                    var child_table_data = [data.data.ct2_payment_detail[i].no_seq, data.data.ct2_payment_detail[i].payment_type, data.data.ct2_payment_detail[i].paying_method, data.data.ct2_payment_detail[i].paying_method_label, data.data.ct2_payment_detail[i].paying, data.data.ct2_payment_detail[i].payment_notes, @if($page_data["page_method_name"] != "View") '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>' @else '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>' @endif, data.data.ct2_payment_detail[i].id];
                    if(dttb.row.add(child_table_data).draw( false )){

                    }
                }
            }
        cto_loading_hide();
    },
        error: function (err) {
            // console.log(err);
            if (err.status >= 400 && err.status <= 500) {
                $.toast({
                    text: err.status+" "+err.responseJSON.message,
                    heading: 'Status',
                    icon: 'warning',
                    showHideTransition: 'fade',
                    allowToastClose: true,
                    hideAfter: 3000,
                    position: 'mid-center',
                    textAlign: 'left'
                });
            }
            cto_loading_hide();
        }
    });
}
    @endif
function addChildTable_ct1_purchasing_detail(childtablename){
    $("select[name='product']").empty();
    $("input[name='product_label']").val("");
    $("input[name='purchasing_price']").val("");
    $("input[name='quantity']").val("");
    $("input[name='discount_percentage']").val("");
    $("input[name='discount_total']").val("");
    $("input[name='total']").val("");
    $("select[name='warehouse']").empty();
    $("input[name='warehouse_label']").val("");
    $("input[name='available_stock']").val("");

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropAdd_ct1_purchasing_detail" class="btn btn-primary">Add Row</button>');
    @endif

    $("#staticBackdropAdd_ct1_purchasing_detail").click(function(e){
        e.preventDefault;
        var dttb = $('#ctct1_purchasing_detail').DataTable();

        var no_seq = dttb.rows().count();
        var product = $("select[name='product'] option").filter(':selected').val();
        if(!product){
            product = null;
        }
        var product_label = $("input[name='product_label']").val();
        var purchasing_price = $("input[name='purchasing_price']").val();
        var quantity = $("input[name='quantity']").val();
        var discount_percentage = $("input[name='discount_percentage']").val();
        var discount_total = $("input[name='discount_total']").val();
        var total = $("input[name='total']").val();
        var warehouse = $("select[name='warehouse'] option").filter(':selected').val();
        if(!warehouse){
            warehouse = null;
        }
        var warehouse_label = $("input[name='warehouse_label']").val();

        var child_table_data = [no_seq+1, product, product_label, purchasing_price, quantity, discount_percentage, discount_total, total, warehouse, warehouse_label, '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>', null];

        if(validatequickModalForm_ct1_purchasing_detail()){
            if(dttb.row.add(child_table_data).draw( false )){
                var purchasing_detail_total = getTotalPrice();
                $("#purchasing_detail_total").val(purchasing_detail_total).trigger('change');
                $('#staticBackdrop_ct1_purchasing_detail').modal('hide');
            }
        }
    });
}

function showChildTable_ct1_purchasing_detail(childtablename, data){
    $("select[name='product']").empty();
    var newState = new Option(data.data()[2], data.data()[1], true, false);
    $("#product").append(newState).trigger('change');
    $("input[name='product_label']").val(data.data()[2]);
    $("input[name='purchasing_price']").val(data.data()[3]);
    $("input[name='quantity']").val(data.data()[4]);
    $("input[name='discount_percentage']").val(data.data()[5]);
    $("input[name='discount_total']").val(data.data()[6]);
    $("input[name='total']").val(data.data()[7]);
    $("select[name='warehouse']").empty();
    var newState = new Option(data.data()[9], data.data()[8], true, false);
    $("#warehouse").append(newState).trigger('change');
    $("input[name='warehouse_label']").val(data.data()[9]);

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropUpdate_ct1_purchasing_detail" class="btn btn-primary">Update</button>');
    @endif

    $("#staticBackdropUpdate_ct1_purchasing_detail").click(function(e){
        var temp = data.data();
        temp[1] = $("select[name='product'] option").filter(':selected').val();
        if(!product){
            product = null;
        }
        temp[2] = $("input[name='product_label']").val();
        temp[3] = $("input[name='purchasing_price']").val();
        temp[4] = $("input[name='quantity']").val();
        temp[5] = $("input[name='discount_percentage']").val();
        temp[6] = $("input[name='discount_total']").val();
        temp[7] = $("input[name='total']").val();
        temp[8] = $("select[name='warehouse'] option").filter(':selected').val();
        if(!warehouse){
            warehouse = null;
        }
        temp[9] = $("input[name='warehouse_label']").val();
        if( validatequickModalForm_ct1_purchasing_detail() ){
            data.data(temp).invalidate();
            var purchasing_detail_total = getTotalPrice();
            $("#purchasing_detail_total").val(purchasing_detail_total).trigger('change');
            $("#staticBackdrop_ct1_purchasing_detail").modal("hide");
        }
    });
}

function addChildTable_ct2_payment_detail(childtablename){
    $("input[name='payment_type']").val("");
    $("select[name='paying_method']").selectedIndex = -1;
    $("input[name='paying_method_label']").val("");
    $("input[name='paying']").val("");
    $("input[name='payment_notes']").val("");

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropAdd_ct2_payment_detail" class="btn btn-primary">Add Row</button>');
    @endif

    $("#staticBackdropAdd_ct2_payment_detail").click(function(e){
        e.preventDefault;
        var dttb = $('#ctct2_payment_detail').DataTable();

        var no_seq = dttb.rows().count();
        var payment_type = $("input[name='payment_type']").val();
        var paying_method = $("select[name='paying_method'] option").filter(':selected').val();
        if(!paying_method){
            paying_method = null;
        }
        var paying_method_label = $("input[name='paying_method_label']").val();
        var paying = $("input[name='paying']").val();
        var payment_notes = $("textarea[name='payment_notes']").val();

        var child_table_data = [no_seq+1, payment_type, paying_method, paying_method_label, paying, payment_notes, '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>', null];

        if(validatequickModalForm_ct2_payment_detail()){
            if(dttb.row.add(child_table_data).draw( false )){
                var buying_total = getbuyingTotal();
                $("#buying_total").val(buying_total).trigger('change');
                $('#staticBackdrop_ct2_payment_detail').modal('hide');
            }
        }
    });
}

function showChildTable_ct2_payment_detail(childtablename, data){
    $("input[name='payment_type']").val(data.data()[1]);
    $("select[name='paying_method']").val(data.data()[2]);
    $("select[name='paying_method']").select2().trigger('change');
    $("input[name='paying_method_label']").val(data.data()[3]);
    $("input[name='paying']").val(data.data()[4]);
    $("input[name='payment_notes']").val(data.data()[5]);

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropUpdate_ct2_payment_detail" class="btn btn-primary">Update</button>');
    @endif

    $("#staticBackdropUpdate_ct2_payment_detail").click(function(e){
        var temp = data.data();
        temp[1] = $("input[name='payment_type']").val();
        temp[2] = $("select[name='paying_method'] option").filter(':selected').val();
        if(!paying_method){
            paying_method = null;
        }
        temp[3] = $("input[name='paying_method_label']").val();
        temp[4] = $("input[name='paying']").val();
        temp[5] = $("input[name='payment_notes']").val();
        if( validatequickModalForm_ct2_payment_detail() ){
            data.data(temp).invalidate();
            var buying_total = getbuyingTotal();
            $("#buying_total").val(buying_total).trigger('change');
            $("#staticBackdrop_ct2_payment_detail").modal("hide");
        }
    });
}


function validatequickModalForm_ct1_purchasing_detail(){
    var validation = $("#quickModalForm_ct1_purchasing_detail").validate({
    rules: {
        product :{
            required: true
        },
        purchasing_price :{
            required: true,
            number: true
        },
        quantity :{
            required: true,
            number: true
        },
        discount_percentage :{
            number: true,
            max:100
        },
        discount_total :{
            number: true
        },
        total :{
            required: true,
            number: true
        },
        warehouse :{
            required: true
        },
    },
    messages: {
        product :{
            required: "Produk harus diisi!!"
        },
        purchasing_price :{
            required: "Harga Jual harus diisi!!",
            number: "Harga Jual harus berupa angka!!"
        },
        quantity :{
            required: "Kuantitas harus diisi!!",
            number: "Kuantitas harus berupa angka!!"
        },
        discount_percentage :{
            number: "Persen Diskon harus berupa angka!!",
            max: "Persen Diskon maksimal 100!!"
        },
        discount_total :{
            number: "Nominal Diskon harus berupa angka!!"
        },
        total :{
            required: "Total harus diisi!!",
            number: "Total harus berupa angka!!"
        },
        warehouse :{
            required: "Gudang harus diisi!!"
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".col-sm-6").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
    });

    validation.form();
    if(validation.errorList.length > 0){
        return false;
    }else{
        return true;
    }
}

function validatequickModalForm_ct2_payment_detail(){
    var validation = $("#quickModalForm_ct2_payment_detail").validate({
    rules: {
        payment_type :{
            required: true
        },
        paying_method :{
            required: true
        },
        paying :{
            required: true,
            number: true
        },
    },
    messages: {
        payment_type :{
            required: "Tipe Pembayaran harus diisi!!"
        },
        paying_method :{
            required: "Metode Pembayaran harus diisi!!"
        },
        paying :{
            required: "Nominal Bayar harus diisi!!",
            number: "Nominal Bayar harus berupa angka!!"
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".col-sm-6").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
    });

    validation.form();
    if(validation.errorList.length > 0){
        return false;
    }else{
        return true;
    }
}


function getTotalPrice(){
    var table = $("#ctct1_purchasing_detail").DataTable().rows().data();
    var total_price = 0;
    for(var i = 0; i < table.length; i++){
        total_price += parseFloat(table[i][7]);
    }
    return total_price;
}

function getbuyingTotal(){
    var table = $("#ctct2_payment_detail").DataTable().rows().data();
    var buying_total = 0;
    for(var i = 0; i < table.length; i++){
        buying_total += parseFloat(table[i][4]);
    }
    return buying_total;
}

function setPurchasingPrice(id){
    cto_loading_show();
    $.ajax({
        url: "/getitembuyingprice",
        type: "post",
        data: {
            id: id,
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            if($("#quantity").val() == "" || $("#quantity").val() == 0){
                $("#quantity").val(1);
            }
            $("#purchasing_price").val(data.data.product.buying_price).trigger("change");
            $("#discount_percentage").val(data.data.product.discount_percentage).trigger("change");
            cto_loading_hide();
        },
        error: function (err) {
            // console.log(err);
            if (err.status >= 400 && err.status <= 500) {
                $.toast({
                    text: err.status+" "+err.responseJSON.message,
                    heading: 'Status',
                    icon: 'warning',
                    showHideTransition: 'fade',
                    allowToastClose: true,
                    hideAfter: 3000,
                    position: 'mid-center',
                    textAlign: 'left'
                });
            }
            cto_loading_hide();
        }
    });
}

function setAvailableStock(){
    if(!$("select[name=product]").val() || !$("select[name=warehouse]").val()){
        return;
    }
    cto_loading_show();
    $.ajax({
        url: "/getavailablestock",
        type: "post",
        data: {
            product: $("select[name=product]").val(),
            warehouse: $("select[name=warehouse]").val(),
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            $("#available_stock").val(data.data.stock).trigger("change");
            cto_loading_hide();
        },
        error: function (err) {
            // console.log(err);
            if (err.status >= 400 && err.status <= 500) {
                $.toast({
                    text: err.status+" "+err.responseJSON.message,
                    heading: 'Status',
                    icon: 'warning',
                    showHideTransition: 'fade',
                    allowToastClose: true,
                    hideAfter: 3000,
                    position: 'mid-center',
                    textAlign: 'left'
                });
            }
            cto_loading_hide();
        }
    });
}
</script>