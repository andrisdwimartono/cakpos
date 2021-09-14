<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bundle;
use App\Models\Bundle_detail;
use App\Models\Product;

class BundleController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Paket Produk",
            "page_data_urlname" => "bundle",
            "fields" => [
                "bundle_name" => "text",
                "bundle_code" => "text",
                "ct1_bundle_detail" => "childtable",
                "total_price" => "float",
                "discount_percentage_bundle" => "float",
                "discount_total_bundle" => "float",
                "total_bundle" => "float"
            ],
            "fieldschildtable" => [
                "ct1_bundle_detail" => [
                    "company_id" => "be_session",
                    "product" => "link",
                    "quantity" => "float",
                    "selling_price" => "float",
                    "discount_percentage" => "float",
                    "discount_total" => "float",
                    "total" => "float"
                ]
            ],
            "fieldlink" => [
                "product" => "products"
            ]
        ];

        $td["fieldsrules"] = [
            "bundle_name" => "required|min:2|max:255",
            "bundle_code" => "required|min:2|max:255",
            "ct1_bundle_detail" => "required",
            "total_price" => "required|numeric",
            "discount_percentage_bundle" => "required|numeric|max:100",
            "discount_total_bundle" => "required|numeric",
            "total_bundle" => "required|numeric"
        ];

        $td["fieldsmessages"] = [
            "required" => ":attribute harus diisi!!",
            "min" => ":attribute minimal :min karakter!!",
            "max" => ":attribute maksimal :max karakter!!",
            "in" => "Tidak ada dalam pilihan :attribute!!",
            "exists" => "Tidak ada dalam :attribute!!",
            "date_format" => "Format tidak sesuai di :attribute!!"
        ];

        $td["fieldsrules_ct1_bundle_detail"] = [
            "product" => "required|exists:products,id",
            "quantity" => "required|numeric",
            "selling_price" => "required|numeric",
            "discount_percentage" => "required|numeric|max:100",
            "discount_total" => "required|numeric",
            "total" => "required|numeric"
        ];

        $td["fieldsmessages_ct1_bundle_detail"] = [
            "required" => ":attribute harus diisi!!",
            "min" => ":attribute minimal :min karakter!!",
            "max" => ":attribute maksimal :max karakter!!",
            "in" => "Tidak ada dalam pilihan :attribute!!",
            "exists" => "Tidak ada dalam :attribute!!",
            "date_format" => "Format tidak sesuai di :attribute!!"
        ];

        return $td;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "List";
        $page_data["footer_js_page_specific_script"] = ["paging.page_specific_script.footer_js_list"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_list"];
        
        return view("bundle.list", ["page_data" => $page_data]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Create";
        $page_data["footer_js_page_specific_script"] = ["bundle.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("bundle.create", ["page_data" => $page_data]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $page_data = $this->tabledesign();
        $rules_ct1_bundle_detail = $page_data["fieldsrules_ct1_bundle_detail"];
        $requests_ct1_bundle_detail = json_decode($request->ct1_bundle_detail, true);
        foreach($requests_ct1_bundle_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_bundle_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_bundle_detail, $ct_messages);
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $id = Bundle::create([
                "bundle_name"=> $request->bundle_name,
                "company_id"=> Auth::user()->company_id,
                "bundle_code"=> $request->bundle_code,
                "total_price"=> $request->total_price,
                "discount_percentage_bundle"=> $request->discount_percentage_bundle,
                "discount_total_bundle"=> $request->discount_total_bundle,
                "total_bundle"=> $request->total_bundle,
                "user_creator_id"=> Auth::user()->id
            ])->id;

            foreach($requests_ct1_bundle_detail as $ct_request){
                Bundle_detail::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "company_id"=> Auth::user()->company_id,
                    "product"=> $ct_request["product"],
                    "product_label"=> $ct_request["product_label"],
                    "quantity"=> $ct_request["quantity"],
                    "selling_price"=> $ct_request["selling_price"],
                    "discount_percentage"=> $ct_request["discount_percentage"],
                    "discount_total"=> $ct_request["discount_total"],
                    "total"=> $ct_request["total"],
                    "user_creator_id" => Auth::user()->id
                ]);
            }

            return response()->json([
                'status' => 201,
                'message' => 'Created with id '.$id,
                'data' => ['id' => $id]
            ]);
        }
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function show(Bundle $bundle)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["bundle.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $bundle->id;
        return view("bundle.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Bundle $bundle)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["bundle.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $bundle->id;
        return view("bundle.create", ["page_data" => $page_data]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $page_data = $this->tabledesign();
        $rules_ct1_bundle_detail = $page_data["fieldsrules_ct1_bundle_detail"];
        $requests_ct1_bundle_detail = json_decode($request->ct1_bundle_detail, true);
        foreach($requests_ct1_bundle_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_bundle_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_bundle_detail, $ct_messages);
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            Bundle::where("id", $id)->update([
                "bundle_name"=> $request->bundle_name,
                "company_id"=> Auth::user()->company_id,
                "bundle_code"=> $request->bundle_code,
                "total_price"=> $request->total_price,
                "discount_percentage_bundle"=> $request->discount_percentage_bundle,
                "discount_total_bundle"=> $request->discount_total_bundle,
                "total_bundle"=> $request->total_bundle,
                "user_updater_id"=> Auth::user()->id
            ]);

            $new_menu_field_ids = array();
            foreach($requests_ct1_bundle_detail as $ct_request){
                if(isset($ct_request["id"])){
                    Bundle_detail::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                "company_id"=> Auth::user()->company_id,
                    "product"=> $ct_request["product"],
                    "product_label"=> $ct_request["product_label"],
                    "quantity"=> $ct_request["quantity"],
                    "selling_price"=> $ct_request["selling_price"],
                    "discount_percentage"=> $ct_request["discount_percentage"],
                    "discount_total"=> $ct_request["discount_total"],
                    "total"=> $ct_request["total"],
                        "user_updater_id" => Auth::user()->id
                    ]);
                }else{
                    $idct = Bundle_detail::create([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                    "company_id"=> Auth::user()->company_id,
                    "product"=> $ct_request["product"],
                    "product_label"=> $ct_request["product_label"],
                    "quantity"=> $ct_request["quantity"],
                    "selling_price"=> $ct_request["selling_price"],
                    "discount_percentage"=> $ct_request["discount_percentage"],
                    "discount_total"=> $ct_request["discount_total"],
                    "total"=> $ct_request["total"],
                        "user_creator_id" => Auth::user()->id
                    ])->id;
                    array_push($new_menu_field_ids, $idct);
                }
            }

            foreach(Bundle_detail::whereParentId($id)->get() as $ch){
                    $is_still_exist = false;
                    foreach($requests_ct1_bundle_detail as $ct_request){
                        if($ch->id == $ct_request["id"] || in_array($ch->id, $new_menu_field_ids)){
                            $is_still_exist = true;
                        }
                    }
                    if(!$is_still_exist){
                        Bundle_detail::whereId($ch->id)->delete();
                    }
                }

            return response()->json([
                'status' => 201,
                'message' => 'Id '.$id.' is updated',
                'data' => ['id' => $id]
            ]);
        }
}

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request)
    {
        if($request->ajax()){
            $bundle = Bundle::whereId($request->id)->first();
            if(!$bundle){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Bundle::whereId($request->id)->forceDelete()){
                $results = array(
                    "status" => 204,
                    "message" => "Deleted successfully"
                );
            }

            return response()->json($results);
        }
    }

    public function get_list(Request $request)
    {
        $list_column = array("id", "bundle_name", "bundle_code", "total_price", "discount_percentage_bundle", "total_bundle", "id");
        $keyword = null;
        if(isset($request->search["value"])){
            $keyword = $request->search["value"];
        }

        $orders = array("id", "ASC");
        if(isset($request->order)){
            $orders = array($list_column[$request->order["0"]["column"]], $request->order["0"]["dir"]);
        }

        $limit = null;
        if(isset($request->length) && $request->length != -1){
            $limit = array(intval($request->start), intval($request->length));
        }

        $dt = array();
        $no = 0;
        foreach(Bundle::where(function($q) use ($keyword) {
            $q->where("bundle_name", "LIKE", "%" . $keyword. "%")->orWhere("bundle_code", "LIKE", "%" . $keyword. "%")->orWhere("total_price", "LIKE", "%" . $keyword. "%")->orWhere("discount_percentage_bundle", "LIKE", "%" . $keyword. "%")->orWhere("total_bundle", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "bundle_name", "bundle_code", "total_price", "discount_percentage_bundle", "total_bundle"]) as $bundle){
            $no = $no+1;
            $act = '
            <a href="/bundle/'.$bundle->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/bundle/'.$bundle->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($bundle->id, $bundle->bundle_name, $bundle->bundle_code, $bundle->total_price, $bundle->discount_percentage_bundle, $bundle->total_bundle, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Bundle::get()->count(),
            "recordsFiltered" => intval(Bundle::where(function($q) use ($keyword) {
                $q->where("bundle_name", "LIKE", "%" . $keyword. "%")->orWhere("bundle_code", "LIKE", "%" . $keyword. "%")->orWhere("total_price", "LIKE", "%" . $keyword. "%")->orWhere("discount_percentage_bundle", "LIKE", "%" . $keyword. "%")->orWhere("total_bundle", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $bundle = Bundle::whereId($request->id)->first();
            if(!$bundle){
                abort(404, "Data not found");
            }

            $ct1_bundle_details = Bundle_detail::whereParentId($request->id)->get();

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "ct1_bundle_detail" => $ct1_bundle_details,
                    "bundle" => $bundle
                ]
            );

            return response()->json($results);
        }
    }

    public function getoptions(Request $request)
    {
        $page_data = $this->tabledesign();
        if($request->fieldname && $page_data["fieldsoptions"][$request->fieldname]){
            return response()->json($page_data["fieldsoptions"][$request->fieldname]);
        }else{
            return response()->json();
        }
    }

    public function getlinks(Request $request)
    {
        if ($request->ajax()){
            $page = $request->page;
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $lists = null;
            $count = 0;
            if($request->field == "product"){
                $lists = Product::where(function($q) use ($request) {
                    $q->where("product_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("product_name as text")]);
                $count = Product::count();
            }

            $endCount = $offset + $resultCount;
            $morePages = $endCount > $count;

            $results = array(
                "results" => $lists,
                "pagination" => array(
                    "more" => $morePages
                ),
                "total_count" => $count,
                "incomplete_results" =>$morePages,
                "items" => $lists
            );

            return response()->json($results);
        }
    }

    public function getItemPrice(Request $request)
    {
        if($request->ajax()){
            if(!isset($request->type)){
                $product = Product::whereId($request->id)->select(["id", "selling_price", "discount_percentage"])->first();
            }else{
                if($request->type == "product"){
                    $product = Product::whereId($request->id)->select(["id", "selling_price", "discount_percentage"])->first();
                }else{
                    $product = Bundle::whereId($request->id)->select(["id", DB::raw("total_price as selling_price"), DB::raw("discount_percentage_bundle as discount_percentage")])->first();
                }
            }
            
            if(!$product){
                abort(404, "Data not found");
            }

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "product" => $product
                ]
            );

            return response()->json($results);
        }
    }
}
