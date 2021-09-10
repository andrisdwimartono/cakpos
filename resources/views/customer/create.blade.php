@extends("paging.main")

@section("content")
                <form id="quickForm" action="#">
                @csrf
                    <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="customer_name">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" name="customer_name" class="form-control" id="customer_name" placeholder="Enter Nama" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"></label>
                                <div class="input-group col-sm-6">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="upload_photo_profile" name="upload_photo_profile" onchange="selectingfile('photo_profile');">
                                        <label class="custom-file-label" for="upload_photo_profile">Pilih file Foto</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="btn_photo_profile" disabled>Upload</button>
                                    </div>
                                </div>
                                <input type="hidden" class="custom-file-input" id="photo_profile" name="photo_profile">    
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="id_card_number">No KTP/Passpor/SIM</label>
                                <div class="col-sm-6">
                                    <input type="text" name="id_card_number" class="form-control" id="id_card_number" placeholder="Enter No KTP/Passpor/SIM" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="first_name">Nama Depan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter Nama Depan" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="last_name">Nama Belakang</label>
                                <div class="col-sm-6">
                                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Nama Belakang" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="email">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="phone_1">No HP 1</label>
                                <div class="col-sm-6">
                                    <input type="text" name="phone_1" class="form-control" id="phone_1" placeholder="Enter No HP 1" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="phone_2">No HP 2</label>
                                <div class="col-sm-6">
                                    <input type="text" name="phone_2" class="form-control" id="phone_2" placeholder="Enter No HP 2" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="segment_level">Segmen</label>
                                <div class="col-sm-6">
                                    <select name="segment_level" id="segment_level" class="form-control select2bs4" style="width: 100%;" @if($page_data["page_method_name"] == "View") readonly @endif>

                                    </select>
                                    <input type="hidden" name="segment_level_label" id="segment_level_label">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="member_level">Member Level</label>
                                <div class="col-sm-6">
                                    <select name="member_level" id="member_level" class="form-control select2bs4" style="width: 100%;" @if($page_data["page_method_name"] == "View") readonly @endif>

                                    </select>
                                    <input type="hidden" name="member_level_label" id="member_level_label">
                                </div>
                            </div>
                    </div>
                    @if($page_data["page_method_name"] != "View")
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-9">
                        <button type="submit" class="btn btn-primary" @if($page_data["page_method_name"] == "View") readonly @endif>Submit</button>
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-9">

                        </div>
                    </div>
                    @endif
                </form>
@endsection