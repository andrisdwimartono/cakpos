@if (Auth::user() == null){
    @include('login');
    @php
      die();
    @endphp
@endif

<!-- @php
  $usermenus = Auth::user()->getUserMenu;
  $last_parent_id = 0;
  $parent_active_id = 0;
  $is_granted = 0;
  $parent_menu_name = '';
  $menu_name = '';
@endphp
@foreach ($usermenus as $um)
  @php 
    if(isset($page_data["id"])){
      $um->url = str_replace("{id}",$page_data["id"],$um->url);
    }
    if(Request::is($um->url)){
      $is_granted = $um->is_granted;
      if(isset($um->parent_id)){
        $parent_active_id = $um->parent_id;
        $menu_name = $um->menu_name;
      }
    }
  @endphp
@endforeach -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset ("/assets/cto/img/favicon.ico") }}">

    <title>CAKRUD | {{$page_data["page_method_name"]}} {{$page_data["page_data_name"]}}</title>
  @if(isset($page_data["header_js_page_specific_script"]))
    @foreach($page_data["header_js_page_specific_script"] as $header_js_pss)
  @include($header_js_pss)
    @endforeach
  @endif
  </head>
<body>
  @php
    $path = Request::path();
    $path = explode("/", $path)[0];
    $path = str_replace("create", "", $path);
  @endphp
<input type="hidden" id="cakcurrent_url" value="{{$path}}">
<div class="wrapper">
  @include('paging.sidebar')
  <div id="content">
    <div id="cto_overlay" class="overlay">
      <div id="cto_mengecek"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
    @include('paging.header')
    <!-- <h1>{{$page_data["page_method_name"]}} {{$page_data["page_data_name"]}}</h1> -->
    <!-- @if (isset($usermenus[$parent_active_id]->menu_name))<li class="breadcrumb-item">{{$usermenus[$parent_active_id]->menu_name}}</li>@endif
    <li class="breadcrumb-item active">{{$menu_name}}</li> -->
    <div class="row">
      <div class="col-10">
      @yield('content')
      </div>
      <div id="cakrightpane" class="col-2 bg-light">
      for right pane
      </div>
    </div>
  </div>
</div>
@include('paging.footer')

 
@if(isset($page_data["footer_js_page_specific_script"]))
  @foreach($page_data["footer_js_page_specific_script"] as $footer_js_pss)
@include($footer_js_pss)
  @endforeach
@endif
</body>
</html>
