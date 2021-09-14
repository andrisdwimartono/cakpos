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

    $.validator.setDefaults({
        submitHandler: function (form, event) {
            event.preventDefault();
            cto_loading_show();
            var quickForm = $("#quickForm");
            var ctct1_bundle_detail = [];
            var table = $("#ctct1_bundle_detail").DataTable().rows().data();
            for(var i = 0; i < table.length; i++){
                ctct1_bundle_detail.push({"no_seq": table[i][0], "product": table[i][1], "product_label": table[i][2], "quantity": table[i][3], "selling_price": table[i][4], "discount_percentage": table[i][5], "discount_total": table[i][6], "total": table[i][7], "id": table[i][table.columns().header().length-1]});
            }
            $("#ct1_bundle_detail").val(JSON.stringify(ctct1_bundle_detail));
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
                            if(i == "company_id" || i == "product" || i == "product_label" || i == "quantity" || i == "selling_price" || i == "discount_percentage" || i == "discount_total" || i == "total"){
                                errors["ct1_bundle_detail"] = error[0];
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
    placeholder: "Pilih Satu",
    allowClear: true,
    theme: "bootstrap4" @if($page_data["page_method_name"] == "View"),
    disabled: true @endif
});

$.fn.modal.Constructor.prototype._enforceFocus = function() {

};

$("#product").on("change", function() {
    $("#product_label").val($("#product option:selected").text());
    setSellingPrice($("#product option:selected").val());
});

$("#total_price").on("change", function() {
    var total_price = $("#total_price").val();
    var discount_percentage_bundle = $("#discount_percentage_bundle").val();
    $("#discount_total_bundle").val(total_price*discount_percentage_bundle/100);
    var discount_total_bundle = $("#discount_total_bundle").val();
    $("#total_bundle").val(total_price-discount_total_bundle);
});

$("#discount_percentage_bundle").on("change", function() {
    var total_price = $("#total_price").val();
    var discount_percentage_bundle = $("#discount_percentage_bundle").val();
    $("#discount_total_bundle").val(total_price*discount_percentage_bundle/100);
    var discount_total_bundle = $("#discount_total_bundle").val();
    $("#total_bundle").val(total_price-discount_total_bundle);
});

$("#discount_total_bundle").on("change", function() {
    var total_price = $("#total_price").val();
    var discount_total_bundle = $("#discount_total_bundle").val();
    $("#discount_percentage_bundle").val(discount_total_bundle*100/total_price);
    var discount_total_bundle = $("#discount_total_bundle").val();
    $("#total_bundle").val(total_price-discount_total_bundle);
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
});

$("#discount_percentage").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_percentage = $("#discount_percentage").val();
    $("#discount_total").val(selling_price*discount_percentage/100);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
});

$("#discount_total").on("change", function() {
    var quantity = $("#quantity").val();
    var selling_price = $("#selling_price").val();
    var discount_total = $("#discount_total").val();
    $("#discount_percentage").val(discount_total*100/selling_price);
    var discount_total = $("#discount_total").val();
    $("#total").val((selling_price-discount_total)*quantity);
});

var fields = $("#quickForm").serialize();

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

$("#quickForm").validate({
    rules: {
        bundle_name :{
            required: true,
            minlength:2,
            maxlength:255
        },
        bundle_code :{
            required: true,
            minlength:2,
            maxlength:255
        },
        total_price :{
            required: true,
            number: true
        },
        discount_percentage_bundle :{
            required: true,
            number: true,
            max:100
        },
        discount_total_bundle :{
            required: true,
            number: true
        },
        total_bundle :{
            required: true,
            number: true
        },
    },
    messages: {
        bundle_name :{
            required: "Nama Paket harus diisi!!",
            minlength: "Nama Paket minimal 2 karakter!!",
            maxlength: "Nama Paket maksimal 255 karakter!!"
        },
        bundle_code :{
            required: "Kode Paket harus diisi!!",
            minlength: "Kode Paket minimal 2 karakter!!",
            maxlength: "Kode Paket maksimal 255 karakter!!"
        },
        total_price :{
            required: "Harga Jual Paket harus diisi!!",
            number: "Harga Jual Paket harus berupa angka!!"
        },
        discount_percentage_bundle :{
            required: "Persen Diskon Paket harus diisi!!",
            number: "Persen Diskon Paket harus berupa angka!!",
            max: "Persen Diskon Paket maksimal 100!!"
        },
        discount_total_bundle :{
            required: "Nominal Diskon harus diisi!!",
            number: "Nominal Diskon harus berupa angka!!"
        },
        total_bundle :{
            required: "Total harus diisi!!",
            number: "Total harus berupa angka!!"
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

$("#quickModalForm_ct1_bundle_detail").validate({
    rules: {
        product :{
            required: true
        },
        quantity :{
            required: true,
            number: true
        },
        selling_price :{
            required: true,
            number: true
        },
        discount_percentage :{
            required: true,
            number: true,
            max:100
        },
        discount_total :{
            required: true,
            number: true
        },
        total :{
            required: true,
            number: true
        },
    },
    messages: {
        product :{
            required: "Produk harus diisi!!"
        },
        quantity :{
            required: "Kuantitas harus diisi!!",
            number: "Kuantitas harus berupa angka!!"
        },
        selling_price :{
            required: "Harga Jual harus diisi!!",
            number: "Harga Jual harus berupa angka!!"
        },
        discount_percentage :{
            required: "Persen Diskon harus diisi!!",
            number: "Persen Diskon harus berupa angka!!",
            max: "Persen Diskon maksimal 100!!"
        },
        discount_total :{
            required: "Nominal Diskon harus diisi!!",
            number: "Nominal Diskon harus berupa angka!!"
        },
        total :{
            required: "Total harus diisi!!",
            number: "Total harus berupa angka!!"
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
    var table_ct1_bundle_detail = $("#ctct1_bundle_detail").DataTable({
        @if($page_data["page_method_name"] != "View")
        rowReorder: true,
        @endif
        //add button
        dom: "Bfrtip" @if($page_data["page_method_name"] != "View") ,
        buttons: [
            {
                text: "New",
                action: function ( e, dt, node, config ) {
                    $("#staticBackdrop_ct1_bundle_detail").modal({"show": true});
                    addChildTable_ct1_bundle_detail("staticBackdrop_ct1_bundle_detail");
                }
            }
        ]
        @endif
    });

    table_ct1_bundle_detail.column(table_ct1_bundle_detail.columns().header().length-1).visible(false);
    table_ct1_bundle_detail.column(0).visible(false);
    table_ct1_bundle_detail.column(1).visible(false);
    table_ct1_bundle_detail.column(5).visible(false);

    $("#ctct1_bundle_detail tbody").on( "click", ".row-show", function () {
        $("#staticBackdrop_ct1_bundle_detail").modal({"show": true});
        showChildTable_ct1_bundle_detail("staticBackdrop_ct1_bundle_detail", table_ct1_bundle_detail.row( $(this).parents("tr") ));
    } );

    $("#staticBackdropClose_ct1_bundle_detail").click(function(){
        $("#staticBackdrop_ct1_bundle_detail").modal("hide");
    });

    table_ct1_bundle_detail.on( "row-reorder", function ( e, diff, edit ) {
            var result = "Reorder started on row: "+edit.triggerRow.data()[1]+"<br>";
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table_ct1_bundle_detail.row( diff[i].node ).data();
                result += rowData[1]+" updated to be in position "+
                    diff[i].newData+" (was "+diff[i].oldData+")<br>";
            }
        $("#result").html( "Event result:<br>"+result );
    } );
    $("#ctct1_bundle_detail tbody").on("click", ".row-delete", function () {
        table_ct1_bundle_detail.row($(this).parents("tr")).remove().draw();
    });

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
                if(["ewfsdfsafdsafasdfasdferad"].includes(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i])){
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

            $("#ctct1_bundle_detail").DataTable().clear().draw();
            if(data.data.ct1_bundle_detail.length > 0){
                for(var i = 0; i < data.data.ct1_bundle_detail.length; i++){
                    var dttb = $('#ctct1_bundle_detail').DataTable();
                    var child_table_data = [data.data.ct1_bundle_detail[i].no_seq, data.data.ct1_bundle_detail[i].product, data.data.ct1_bundle_detail[i].product_label, data.data.ct1_bundle_detail[i].quantity, data.data.ct1_bundle_detail[i].selling_price, data.data.ct1_bundle_detail[i].discount_percentage, data.data.ct1_bundle_detail[i].discount_total, data.data.ct1_bundle_detail[i].total, @if($page_data["page_method_name"] != "View") '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>' @else '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>' @endif, data.data.ct1_bundle_detail[i].id];
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
function addChildTable_ct1_bundle_detail(childtablename){
    $("input[name='company_id']").val("");
    $("select[name='product']").empty();
    $("input[name='product_label']").val("");
    $("input[name='quantity']").val("");
    $("input[name='selling_price']").val("");
    $("input[name='discount_percentage']").val("");
    $("input[name='discount_total']").val("");
    $("input[name='total']").val("");

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropAdd_ct1_bundle_detail" class="btn btn-primary">Add Row</button>');
    @endif

    $("#staticBackdropAdd_ct1_bundle_detail").click(function(e){
        e.preventDefault;
        var dttb = $('#ctct1_bundle_detail').DataTable();

        var no_seq = dttb.rows().count();
        var product = $("select[name='product'] option").filter(':selected').val();
        if(!product){
            product = null;
        }
        var product_label = $("input[name='product_label']").val();
        var quantity = $("input[name='quantity']").val();
        var selling_price = $("input[name='selling_price']").val();
        var discount_percentage = $("input[name='discount_percentage']").val();
        var discount_total = $("input[name='discount_total']").val();
        var total = $("input[name='total']").val();

        var child_table_data = [no_seq+1, product, product_label, quantity, selling_price, discount_percentage, discount_total, total, '<div class="row-show"><i class="fa fa-eye" style="color:blue;cursor: pointer;"></i></div>     <div class="row-delete"><i class="fa fa-trash" style="color:red;cursor: pointer;"></i></div>', null];

        if(validatequickModalForm_ct1_bundle_detail()){
            if(dttb.row.add(child_table_data).draw( false )){
                var total_price = getTotalPrice();
                $("#total_price").val(total_price).trigger('change');
                $('#staticBackdrop_ct1_bundle_detail').modal('hide');
            }
        }
    });
}

function showChildTable_ct1_bundle_detail(childtablename, data){
    $("select[name='product']").empty();
    var newState = new Option(data.data()[2], data.data()[1], true, false);
    $("#product").append(newState).trigger('change');
    $("input[name='product_label']").val(data.data()[2]);
    $("input[name='quantity']").val(data.data()[3]);
    $("input[name='selling_price']").val(data.data()[4]);
    $("input[name='discount_percentage']").val(data.data()[5]);
    $("input[name='discount_total']").val(data.data()[6]);
    $("input[name='total']").val(data.data()[7]);

    @if($page_data["page_method_name"] != "View")
    $("#"+childtablename+" .modal-footer").html('<button type="button" id="staticBackdropUpdate_ct1_bundle_detail" class="btn btn-primary">Update</button>');
    @endif

    $("#staticBackdropUpdate_ct1_bundle_detail").click(function(e){
        var temp = data.data();
        temp[1] = $("select[name='product'] option").filter(':selected').val();
        if(!product){
            product = null;
        }
        temp[2] = $("input[name='product_label']").val();
        temp[3] = $("input[name='quantity']").val();
        temp[4] = $("input[name='selling_price']").val();
        temp[5] = $("input[name='discount_percentage']").val();
        temp[6] = $("input[name='discount_total']").val();
        temp[7] = $("input[name='total']").val();
        if( validatequickModalForm_ct1_bundle_detail() ){
            data.data(temp).invalidate();
            var total_price = getTotalPrice();
            $("#total_price").val(total_price).trigger('change');
            $("#staticBackdrop_ct1_bundle_detail").modal("hide");
        }
    });
}

function validatequickModalForm_ct1_bundle_detail(){
    var validation = $("#quickModalForm_ct1_bundle_detail").validate({
    rules: {
        product :{
            required: true
        },
        quantity :{
            required: true,
            number: true
        },
        selling_price :{
            required: true,
            number: true
        },
        discount_percentage :{
            required: true,
            number: true,
            max:100
        },
        discount_total :{
            required: true,
            number: true
        },
        total :{
            required: true,
            number: true
        },
    },
    messages: {
        product :{
            required: "Produk harus diisi!!"
        },
        quantity :{
            required: "Kuantitas harus diisi!!",
            number: "Kuantitas harus berupa angka!!"
        },
        selling_price :{
            required: "Harga Jual harus diisi!!",
            number: "Harga Jual harus berupa angka!!"
        },
        discount_percentage :{
            required: "Persen Diskon harus diisi!!",
            number: "Persen Diskon harus berupa angka!!",
            max: "Persen Diskon maksimal 100!!"
        },
        discount_total :{
            required: "Nominal Diskon harus diisi!!",
            number: "Nominal Diskon harus berupa angka!!"
        },
        total :{
            required: "Total harus diisi!!",
            number: "Total harus berupa angka!!"
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
    var table = $("#ctct1_bundle_detail").DataTable().rows().data();
    var total_price = 0;
    for(var i = 0; i < table.length; i++){
        total_price += parseInt(table[i][7]);
    }
    return total_price;
}

function setSellingPrice(id){
    cto_loading_show();
    $.ajax({
        url: "/getitemprice",
        type: "post",
        data: {
            id: id,
            _token: $("#quickForm input[name=_token]").val()
        },
        success: function(data){
            if($("#quantity").val() == "" || $("#quantity").val() == 0){
                $("#quantity").val(1);
            }
            $("#selling_price").val(data.data.product.selling_price).trigger("change");
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