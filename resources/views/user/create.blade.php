@extends("paging.main")

@section("content")
                <form id="quickForm" action="#">
                @csrf
                    <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="name">Name</label>
                                <div class="col-sm-6">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="email">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email" @if($page_data["page_method_name"] == "View") readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="password">Password</label>
                                <div class="col-sm-6">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" @if($page_data["page_method_name"] == "View") readonly @endif>
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
                    </div>
                    @if($page_data["page_method_name"] != "View")
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-4">
                        <button type="submit" class="btn btn-primary" @if($page_data["page_method_name"] == "View") readonly @endif>Submit</button>
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-4">

                        </div>
                    </div>
                    @endif
                </form>
@endsection