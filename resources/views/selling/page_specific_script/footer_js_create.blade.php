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
    var tgl = (dt.getDate()<10?"0"+dt.getDate():dt.getDate())+"/"+((dt.getMonth()+1)<10?"0"+(dt.getMonth()+1):(dt.getMonth()+1))+"/"+dt.getFullYear()+" "+(dt.getHours()<10?"0"+dt.getHours():dt.getHours())+":"+(dt.getMinutes()<10?"0"+dt.getMinutes():dt.getMinutes())
    @if($page_data["page_method_name"] != "View")
    $("#selling_datetime").datetimepicker({
        format:"dd/mm/yyyy HH:MM",
        modal: true,
        footer: true,
        uiLibrary: 'bootstrap'@if($page_data["page_method_name"] == "Create"),
        value: tgl @endif
    });
    @endif
    @if($page_data["page_method_name"] != "View")
    $("#paying_datetime").datetimepicker({
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
            var ctct1_selling_detail = [];
            var table = $("#ctct1_selling_detail").DataTable().rows().data();
            for(var i = 0; i < table.length; i++){
                ctct1_selling_detail.push({"no_seq": table[i][0], "product_or_bundle": table[i][1], "product_or_bundle_label": table[i][2], "is_bundle": table[i][3], "product": table[i][4], "product_label": table[i][5], "bundle": table[i][6], "bundle_label": table[i][7], "selling_price": table[i][8], "quantity": table[i][9], "discount_percentage": table[i][10], "discount_total": table[i][11], "total": table[i][12], "warehouse": table[i][13], "warehouse_label": table[i][14], "id": table[i][table.columns().header().length-1]});
            }
            $("#ct1_selling_detail").val(JSON.stringify(ctct1_selling_detail));

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
                            if(i == "product_or_bundle" || i == "product_or_bundle_label" || i == "is_bundle" || i == "product" || i == "product_label" || i == "bundle" || i == "bundle_label" || i == "selling_price" || i == "quantity" || i == "discount_percentage" || i == "discount_total" || i == "total" || i == "warehouse" || i == "warehouse_label"){
                                errors["ct1_selling_detail"] = error[0];
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



$("#customer").on("change", function() {
    $("#customer_label").val($("#customer option:selected").text());
    setDiscount();
});

$("#product_or_bundle").on("change", function() {
    $("#product_or_bundle_label").val($("#product_or_bundle option:selected").text());
    if($("#product_or_bundle").val().includes("BUN-")){
        $("#is_bundle").val("yes");
        $("#bundle").val($("#product_or_bundle").val().replace("BUN-", ""));
        $("#bundle_label").val($("#product_or_bundle option:selected").text());
        $("#product").val("");
        $("#product_label").val("");
        setSellingPrice($("#product_or_bundle").val().replace("BUN-", ""), "bundle");
    }else{
        $("#is_bundle").val("");
        $("#bundle").val("");
        $("#bundle_label").val("");
        $("#product").val($("#product_or_bundle").val().replace("PRO-", ""));
        $("#product_label").val($("#product_or_bundle option:selected").text());
        setSellingPrice($("#product_or_bundle").val().replace("PRO-", ""), "product");
    }
    setAvailableStock();
});

$("#warehouse").on("change", function() {
    $("#warehouse_label").val($("#warehouse option:selected").text());
    setAvailableStock();
});

$("#selling_detail_total").on("change", function() {
    var selling_detail_total = $("#selling_detail_total").val();
    var selling_discount_percentage = $("#selling_discount_percentage").val();
    $("#selling_discount_total").val(selling_detail_total*selling_discount_percentage/100);
    var selling_discount_total = $("#selling_discount_total").val();
    $("#selling_total").val(selling_detail_total-selling_discount_total);
});

$("#selling_discount_percentage").on("change", function() {
    var selling_detail_total = $("#selling_detail_total").val();
    var selling_discount_percentage = $("#selling_discount_percentage").val();
    $("#selling_discount_total").val(selling_detail_total*selling_discount_percentage/100);
    var selling_discount_total = $("#selling_discount_total").val();
    $("#selling_total").val(selling_detail_total-selling_discount_total);
});

$("#selling_discount_total").on("change", function() {
    var selling_detail_total = $("#selling_detail_total").val();
    var selling_discount_total = $("#selling_discount_total").val();
    $("#selling_discount_percentage").val(selling_discount_total*100/selling_detail_total);
    var selling_discount_total = $("#selling_discount_total").val();
    $("#selling_total").val(selling_detail_total-selling_discount_total);
});

$("#quantity").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(selling_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
});

$("#selling_price").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(selling_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
    $("#paying_total").trigger("change");
});

$("#discount_percentage").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(selling_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
    $("#paying_total").trigger("change");
});

$("#discount_total").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_total = $("#discount_total").val();
    $("#discount_percentage").val(discount_total*100/selling_price);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
    $("#paying_total").trigger("change");
});

$("#paying_method").on("change", function() {
    $("#paying_method_label").val($("#paying_method option:selected").text());
});

$("#paying_total").on("change", function() {
    var selling_total = $("#selling_total").val();
    var paying_total = $("#paying_total").val();
    $("#change_total").val(paying_total-selling_total);
});

var fields = $("#quickForm").serialize();

$.ajax({
    url: "/getoptions{{$page_data["page_data_urlname"]}}",
    type: "post",
    data: {fieldname: "paying_method", 
    _token: $("#quickForm input[name=_token]").val()},
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



$("#customer").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "customer",
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

$("#product_or_bundle").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "product_or_bundle",
                _token: $("input[name=_token]").val()
            }
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            for(var i = 0; i < data.items.length; i++){
                data.items[i].id = data.items[i].type;
            }
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
        selling_name :{
            minlength:2,
            maxlength:255
        },
        selling_datetime :{
            required: true
        },
        paying_datetime :{
            required: true
        },
        selling_detail_total :{
            required: true,
            number: true
        },
        selling_discount_percentage :{
            required: true,
            number: true,
            max:100
        },
        selling_discount_total :{
            required: true,
            number: true
        },
        selling_total :{
            required: true,
            number: true
        },
        paying_total :{
            required: true,
            number: true
        },
        change_total :{
            required: true,
            number: true
        },
    },
    messages: {
        selling_name :{
            minlength: "Kode Penjualan minimal 2 karakter!!",
            maxlength: "Kode Penjualan maksimal 255 karakter!!"
        },
        selling_datetime :{
            required: "Waktu Penjualan harus diisi!!"
        },
        paying_datetime :{
            required: "Waktu Pembayaran harus diisi!!"
        },
        selling_detail_total :{
            required: "Total Detail Penjualan harus diisi!!",
            number: "Total Detail Penjualan harus berupa angka!!"
        },
        selling_discount_percentage :{
            required: "Persen Diskon Penjualan harus diisi!!",
            number: "Persen Diskon Penjualan harus berupa angka!!",
            max: "Persen Diskon Penjualan maksimal 100!!"
        },
        selling_discount_total :{
            required: "Nominal Diskon Penjualan harus diisi!!",
            number: "Nominal Diskon Penjualan harus berupa angka!!"
        },
        selling_total :{
            required: "Total Penjualan harus diisi!!",
            number: "Total Penjualan harus berupa angka!!"
        },
        paying_total :{
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

$("#quickModalForm_ct1_selling_detail").validate({
    rules: {
        product_or_bundle :{
            required: true
        },
        is_bundle :{
            required: true
        },
        selling_price :{
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
        product_or_bundle :{
            required: "Produk / Bundle harus diisi!!"
        },
        is_bundle :{
            required: "Apakah Bundle? harus diisi!!"
        },
        selling_price :{
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
    var table_ct1_selling_detail = $("#ctct1_selling_detail").DataTable({
        @if($page_data["page_method_name"] != "View")
        rowReorder: true,
        @endif
        //add button
        dom: "Bfrtip" @if($page_data["page_method_name"] != "View") ,
        buttons: [
            {
                text: "New",
                action: function ( e, dt, node, config ) {
                    $("#staticBackdrop_ct1_selling_detail").modal({"show": true});
                    addChildTable_ct1_selling_detail("staticBackdrop_ct1_selling_detail");
                }
            }
        ]
        @endif
    });

    table_ct1_selling_detail.column(table_ct1_selling_detail.columns().header().length-1).visible(false);
    table_ct1_selling_detail.column(1).visible(false);
    table_ct1_selling_detail.column(3).visible(false);
    table_ct1_selling_detail.column(4).visible(false);
    table_ct1_selling_detail.column(5).visible(false);
    table_ct1_selling_detail.column(6).visible(false);
    table_ct1_selling_detail.column(7).visible(false);
    table_ct1_selling_detail.column(10).visible(false);
    table_ct1_selling_detail.column(11).visible(false);
    table_ct1_selling_detail.column(13).visible(false);
    table_ct1_selling_detail.column(14).visible(false);

    $("#ctct1_selling_detail tbody").on( "click", ".row-show", function () {
        $("#staticBackdrop_ct1_selling_detail").modal({"show": true});
        showChildTable_ct1_selling_detail("staticBackdrop_ct1_selling_detail", table_ct1_selling_detail.row( $(this).parents("tr") ));
    } );

    $("#staticBackdropClose_ct1_selling_detail").click(function(){
        $("#staticBackdrop_ct1_selling_detail").modal("hide");
    });

    table_ct1_selling_detail.on( "row-reorder", function ( e, diff, edit ) {
            var result = "Reorder started on row: "+edit.triggerRow.data()[1]+"<br>";
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table_ct1_selling_detail.row( diff[i].node ).data();
                result += rowData[1]+" updated to be in position "+
                    diff[i].newData+" (was "+diff[i].oldData+")<br>";
            }
        $("#result").html( "Event result:<br>"+result );
    } );
    $("#ctct1_selling_detail tbody").on("click", ".row-delete", function () {
        table_ct1_selling_detail.row($(this).parents("tr")).remove().draw();
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
            $("#fg_paying_total").removeClass("d-none");
            $("#fg_change_total").removeClass("d-none");
            $("#fg_paying_datetime").removeClass("d-none");
            $("#fg_ct2_payment_detail").removeClass("d-none");
        }else{
            $("#fg_paying_total").addClass("d-none");
            $("#fg_change_total").addClass("d-none");
            $("#fg_paying_datetime").addClass("d-none");
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
                if(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i] == "customer"){
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

            $("#ctct1_selling_detail").DataTable().clear().draw();
            if(data.data.ct1_selling_detail.length > 0){
                for(var i = 0; i < data.data.ct1_selling_detail.length; i++){
                    var dttb = $('#ctct1_selling_detail').DataTable();
                    var child_table_data = [data.data.ct1_selling_detail[i].no_seq, data.data.ct1_selling_detail[i].product_or_bundle, data.data.ct1_selling_detail[i].product_or_bundle_label, data.data.ct1_selling_detail[i].is_bundle, data.data.ct1_selling_detail[i].product, data.data.ct1_selling_detail[i].product_label, data.data.ct1_selling_detail[i].bundle, data.data.ct1_selling_detail[i].bundle_label, data.data.ct1_selling_detail[i].selling_price, data.data.ct1_selling_detail[i].quantity, data.data.ct1_selling_detail[i].discount_percentage, data.data.ct1_selling_detail[i].discount_total, data.data.ct1_selling_detail[i].total, data.data.ct1_selling_detail[i].warehouse, data.data.ct1_selling_detail[i].warehouse_label, @if($page_data["page_method_name"] != "View") '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>' @else '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>' @endif, data.data.ct1_selling_detail[i].id];
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
function addChildTable_ct1_selling_detail(childtablename){
    //$("select[name='product_or_bundle']").selectedIndex = -1;
    $("select[name='product_or_bundle']").empty();
    $("input[name='product_or_bundle_label']").val("");
    $("input[name='is_bundle']").val("");
    $("input[name='product']").val("");
    $("input[name='product_label']").val("");
    $("input[name='bundle']").val("");
    $("input[name='bundle_label']").val("");
    $("input[name='selling_price']").val("");
    $("input[name='quantity']").val("");
    $("input[name='discount_percentage']").val("");
    $("input[name='discount_total']").val("");
    $("input[name='total']").val("");
    $("select[name='warehouse']").empty();
    $("input[name='warehouse_label']").val("");
    $("input[name='available_stock']").val("");

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropAdd_ct1_selling_detail" class="btn btn-primary">Add Row</button>');
    @endif

    $("#staticBackdropAdd_ct1_selling_detail").click(function(e){
        e.preventDefault;
        var dttb = $('#ctct1_selling_detail').DataTable();

        var no_seq = dttb.rows().count();
        var product_or_bundle = $("select[name='product_or_bundle'] option").filter(':selected').val();
        if(!product_or_bundle){
            product_or_bundle = null;
        }
        var product_or_bundle_label = $("input[name='product_or_bundle_label']").val();
        var is_bundle = $("input[name='is_bundle']").val();
        var product = $("input[name='product']").val();
        var product_label = $("input[name='product_label']").val();
        var bundle = $("input[name='bundle']").val();
        var bundle_label = $("input[name='bundle_label']").val();
        var selling_price = $("input[name='selling_price']").val();
        var quantity = $("input[name='quantity']").val();
        var discount_percentage = $("input[name='discount_percentage']").val();
        var discount_total = $("input[name='discount_total']").val();
        var total = $("input[name='total']").val();
        var warehouse = $("select[name='warehouse'] option").filter(':selected').val();
        if(!warehouse){
            warehouse = null;
        }
        var warehouse_label = $("input[name='warehouse_label']").val();

        var child_table_data = [no_seq+1, product_or_bundle, product_or_bundle_label, is_bundle, product, product_label, bundle, bundle_label, selling_price, quantity, discount_percentage, discount_total, total, warehouse, warehouse_label, '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>', null];

        if(validatequickModalForm_ct1_selling_detail()){
            if(dttb.row.add(child_table_data).draw( false )){
                var selling_detail_total = getTotalPrice();
                $("#selling_detail_total").val(selling_detail_total).trigger('change');
                $('#staticBackdrop_ct1_selling_detail').modal('hide');
            }
        }
    });
}

function showChildTable_ct1_selling_detail(childtablename, data){
    $("select[name='product_or_bundle']").empty();
    var newState = new Option(data.data()[2], data.data()[1], true, false);
    $("#product_or_bundle").append(newState).trigger('change');
    $("input[name='product_or_bundle_label']").val(data.data()[2]);
    $("input[name='is_bundle']").val(data.data()[3]);
    $("input[name='product']").val(data.data()[4]);
    $("input[name='product_label']").val(data.data()[5]);
    $("input[name='bundle']").val(data.data()[6]);
    $("input[name='bundle_label']").val(data.data()[7]);
    $("input[name='selling_price']").val(data.data()[8]);
    $("input[name='quantity']").val(data.data()[9]);
    $("input[name='discount_percentage']").val(data.data()[10]);
    $("input[name='discount_total']").val(data.data()[11]);
    $("input[name='total']").val(data.data()[12]);
    $("select[name='warehouse']").empty();
    var newState = new Option(data.data()[14], data.data()[13], true, false);
    $("#warehouse").append(newState).trigger('change');
    $("input[name='warehouse_label']").val(data.data()[14]);

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropUpdate_ct1_selling_detail" class="btn btn-primary">Update</button>');
    @endif

    $("#staticBackdropUpdate_ct1_selling_detail").click(function(e){
        var temp = data.data();
        temp[1] = $("select[name='product_or_bundle'] option").filter(':selected').val();
        if(!product_or_bundle){
            product_or_bundle = null;
        }
        temp[2] = $("input[name='product_or_bundle_label']").val();
        temp[3] = $("input[name='is_bundle']").val();
        temp[4] = $("input[name='product']").val();
        temp[5] = $("input[name='product_label']").val();
        temp[6] = $("input[name='bundle']").val();
        temp[7] = $("input[name='bundle_label']").val();
        temp[8] = $("input[name='selling_price']").val();
        temp[9] = $("input[name='quantity']").val();
        temp[10] = $("input[name='discount_percentage']").val();
        temp[11] = $("input[name='discount_total']").val();
        temp[12] = $("input[name='total']").val();
        temp[13] = $("select[name='warehouse'] option").filter(':selected').val();
        if(!warehouse){
            warehouse = null;
        }
        temp[14] = $("input[name='warehouse_label']").val();
        if( validatequickModalForm_ct1_selling_detail() ){
            data.data(temp).invalidate();
            var selling_detail_total = getTotalPrice();
            $("#selling_detail_total").val(selling_detail_total).trigger('change');
            $("#staticBackdrop_ct1_selling_detail").modal("hide");
        }
    });
}

function addChildTable_ct2_payment_detail(childtablename){
    $("input[name='payment_type']").val("");
    $("select[name='paying_method']").val("").trigger("change");
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
                var paying_total = getPayingTotal();
                $("#paying_total").val(paying_total).trigger('change');
                $('#staticBackdrop_ct2_payment_detail').modal('hide');
            }
        }
    });
}

function showChildTable_ct2_payment_detail(childtablename, data){
    $("input[name='payment_type']").val(data.data()[1]);
    $("select[name='paying_method']").val(data.data()[2]).trigger("change");
    $("input[name='paying_method_label']").val(data.data()[3]);
    $("input[name='paying']").val(data.data()[4]);
    $("textarea[name='payment_notes']").val(data.data()[5]);

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
        temp[5] = $("textarea[name='payment_notes']").val();
        if( validatequickModalForm_ct2_payment_detail() ){
            data.data(temp).invalidate();
            var paying_total = getPayingTotal();
            $("#paying_total").val(paying_total).trigger('change');
            $("#staticBackdrop_ct2_payment_detail").modal("hide");
        }
    });
}


function validatequickModalForm_ct1_selling_detail(){
    var validation = $("#quickModalForm_ct1_selling_detail").validate({
    rules: {
        product_or_bundle :{
            required: true
        },
        is_bundle :{
            required: true
        },
        selling_price :{
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
        product_or_bundle :{
            required: "Produk / Bundle harus diisi!!"
        },
        is_bundle :{
            required: "Apakah Bundle? harus diisi!!"
        },
        selling_price :{
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
    var table = $("#ctct1_selling_detail").DataTable().rows().data();
    var total_price = 0;
    for(var i = 0; i < table.length; i++){
        total_price += parseFloat(table[i][12]);
    }
    return total_price;
}

function getPayingTotal(){
    var table = $("#ctct2_payment_detail").DataTable().rows().data();
    var paying_total = 0;
    for(var i = 0; i < table.length; i++){
        paying_total += parseFloat(table[i][4]);
    }
    return paying_total;
}

function setSellingPrice(id, type){
    cto_loading_show();
    $.ajax({
        url: "/getitemprice",
        type: "post",
        data: {
            id: id,
            type: type,
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            if($("#quantity").val() == "" || $("#quantity").val() == 0){
                $("#quantity").val(1);
            }
            $("#selling_price").val(data.data.product.selling_price).trigger("change");
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
    if(!$("select[name=warehouse]").val()){
        return;
    }
    cto_loading_show();
    $.ajax({
        url: "/getavailablestock",
        type: "post",
        data: {
            is_bundle: $("input[name=is_bundle]").val(),
            product: $("input[name=product]").val(),
            bundle: $("input[name=bundle]").val(),
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

function setDiscount(){
    if(!$("select[name=customer]").val()){
        return;
    }
    cto_loading_show();
    $.ajax({
        url: "/getcustomerdiscount",
        type: "post",
        data: {
            customer: $("select[name=customer]").val(),
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            //get higest discount between customer discount type()
            if(data.data.customer.is_discount_percentage){
                $("#selling_discount_percentage").val(data.data.customer.discount_percentage).trigger("change");
                $.toast({
                    text: "Customer "+data.data.customer.discount_from+" "+data.data.customer.level_name+" mendapatkan diskon sebesar "+data.data.customer.discount_percentage+"%",
                    heading: 'Status',
                    icon: 'success',
                    showHideTransition: 'fade',
                    allowToastClose: true,
                    hideAfter: 3000,
                    position: 'mid-center',
                    textAlign: 'left'
                });
            }else{
                $("#selling_discount_total").val(data.data.customer.discount).trigger("change");
                $.toast({
                    text: "Customer "+data.data.customer.discount_from+" "+data.data.customer.level_name+" mendapatkan diskon sebesar Rp "+data.data.customer.discount+"",
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