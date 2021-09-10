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
                            errors[i] = error[0];
                            validator.showErrors(errors);
                    });
                }
                cto_loading_hide();
            }
        });
    }
});

$("select").select2({
    placeholder: "",
    allowClear: true,
    theme: "bootstrap4" @if($page_data["page_method_name"] == "View"),
    disabled: true @endif
});

$("#segment_level").on("change", function() {
    $("#segment_level_label").val($("#segment_level option:selected").text());
});

$("#member_level").on("change", function() {
    $("#member_level_label").val($("#member_level option:selected").text());
});
var fields = $("#quickForm").serialize();

$("#segment_level").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "segment_level",
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

$("#member_level").select2({
    ajax: {
        url: "/getlinks{{$page_data["page_data_urlname"]}}",
        type: "post",
        dataType: "json",
        data: function(params) {
            return {
                term: params.term || "",
                page: params.page,
                field: "member_level",
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
        customer_name :{
            required: true,
            minlength:2,
            maxlength:255
        },
        id_card_number :{
            required: true,
            maxlength:75
        },
        first_name :{
            maxlength:255
        },
        last_name :{
            required: true,
            minlength:2,
            maxlength:255
        },
        email :{
            maxlength:255
        },
        phone_1 :{
            required: true
        },
        segment_level :{
            required: true
        },
        member_level :{
            required: true
        },
    },
    messages: {
        customer_name :{
            required: "Nama harus diisi!!",
            minlength: "Nama minimal 2 karakter!!",
            maxlength: "Nama maksimal 255 karakter!!"
        },
        id_card_number :{
            required: "No KTP/Passpor/SIM harus diisi!!",
            maxlength: "No KTP/Passpor/SIM maksimal 75 karakter!!"
        },
        first_name :{
            maxlength: "Nama Depan maksimal 255 karakter!!"
        },
        last_name :{
            required: "Nama Belakang harus diisi!!",
            minlength: "Nama Belakang minimal 2 karakter!!",
            maxlength: "Nama Belakang maksimal 255 karakter!!"
        },
        email :{
            maxlength: "Email maksimal 255 karakter!!"
        },
        phone_1 :{
            required: "No HP 1 harus diisi!!"
        },
        segment_level :{
            required: "Segmen harus diisi!!"
        },
        member_level :{
            required: "Member Level harus diisi!!"
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
                if(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i] == "segment_level" || Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i] == "member_level"){
                    if(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]){
                        var newState = new Option(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"_label"], data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]], true, false);
                        $("#"+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]).append(newState).trigger("change");
                    }
                }else{
                    if(["ewfsdfsafdsafasdfasdferad"].includes(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i])){
                        $("input[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").prop("checked", data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                    }else{
                        $("input[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").val(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                        $("textarea[name="+Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]+"]").val(data.data.{{$page_data["page_data_urlname"]}}[Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i]]);
                        if([""photo_profile""].includes(Object.keys(data.data.{{$page_data["page_data_urlname"]}})[i])){
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
function selectingfile(fieldid){
    $("#btn_"+fieldid).removeAttr("disabled");
    $("#btn_"+fieldid).addClass("btn-primary text-white");
    $("#btn_"+fieldid).removeClass("btn-success");
    var filename = document.getElementById("upload_"+fieldid).files[0].name;
    $("label[for=upload_"+fieldid+"]").html(filename);
    $("#btn_"+fieldid).html("Upload");
    $("#"+fieldid).val("");
}

$("#btn_photo_profile").on('click', function(){
    if($("#photo_profile").val() != ""){
        fetch('{{ asset ("/photo_profile/") }}/'+$("#photo_profile").val()).then(resp => resp.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = $("#photo_profile").val();
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(() => {
            $.toast({
                text: 'Download gagal',
                heading: 'Status',
                icon: 'warning',
                showHideTransition: 'fade',
                allowToastClose: true,
                hideAfter: 3000,
                position: 'mid-center',
                textAlign: 'left'
            });
        });
    }else{
        $("#btn_photo_profile").attr("disabled", true);
        $("#btn_photo_profile").removeClass("btn-primary text-white");
        
        var uploadfile = document.getElementById("upload_photo_profile").files[0];
        var name = uploadfile.name;
        var form_data = new FormData();
        var ext = name.split('.').pop().toLowerCase();
        if(jQuery.inArray(ext, ['jpg','JPG','jpeg','JPEG','png','PNG']) == -1){
            $.toast({
                text: "Format file harus '.jpg','.JPG','.jpeg','.JPEG','.png','.PNG'",
                heading: 'Status',
                icon: 'warning',
                showHideTransition: 'fade',
                allowToastClose: true,
                hideAfter: 3000,
                position: 'mid-center',
                textAlign: 'left'
            });
            return;
        }
        var oFReader = new FileReader();
        oFReader.readAsDataURL(uploadfile);
        var f = uploadfile;
        var fsize = f.size||f.fileSize;
        if(fsize > 2000000){
            $.toast({
                text: "Ukuran file terlalu bersar",
                heading: 'Status',
                icon: 'warning',
                showHideTransition: 'fade',
                allowToastClose: true,
                hideAfter: 3000,
                position: 'mid-center',
                textAlign: 'left'
            });
        }else{
            form_data.append("file", uploadfile);
            form_data.append("_token", $("#quickForm input[name=_token]").val());
            form_data.append("menname", "photo_profile");
            $.ajax({
                url:"/uploadfilecustomer",
                method:"POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    $("label[for=upload_photo_profile]").html("Uploading <i class=\"fas fa-spinner fa-pulse\"></i>");
                },
                success:function(data){
                    if(data.status >= 200 && data.status <= 299){
                        $("label[for=upload_photo_profile]").html("Finished upload file");
                        $("#photo_profile").val(data.filename);
                        $("#btn_photo_profile").attr("disabled", false);
                        $("#btn_photo_profile").addClass("btn-success text-white");
                        $("#btn_photo_profile").html("Download");
                    }
                },
                error: function (err) {
                    if (err.status >= 400) {
                        $.toast({
                            text: "Gagal upload",
                            heading: 'Status',
                            icon: 'warning',
                            showHideTransition: 'fade',
                            allowToastClose: true,
                            hideAfter: 3000,
                            position: 'mid-center',
                            textAlign: 'left'
                        });
                    }
                }
            });
        }
    }
});

</script>