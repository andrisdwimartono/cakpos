<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Uom;
use App\Models\Category;
use App\Models\Product_stock;
use App\Models\Warehouse;

class ProductController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Produk",
            "page_data_urlname" => "product",
            "fields" => [
                "product_name" => "text",
                "product_photo" => "upload",
                "produce_code" => "text",
                "uom" => "link",
                "category" => "link",
                "buying_price" => "float",
                "selling_price" => "float",
                "discount_percentage" => "float",
                "discount" => "float",
                "status" => "select",
                "product_stock" => "childtable"
            ],
            "fieldschildtable" => [
                "product_stock" => [
                    "company_id" => "be_session",
                    "warehouse" => "link",
                    "stock" => "float"
                ]
            ],
            "fieldsoptions" => [
                "status" => [
                    ["name" => "Tersedia", "label" => "tersedia"],
                    ["name" => "Tidak Tersedia", "label" => "tidaktersedia"]
                ]
            ],
            "fieldlink" => [
                "uom" => "uoms",
                "category" => "categorys",
                "warehouse" => "warehouses"
            ]
        ];

        $status_list = "Tersedia,Tidak Tersedia";

        $td["fieldsrules"] = [
            "product_name" => "required|min:2|max:255",
            "produce_code" => "max:255",
            "uom" => "exists:uoms,id",
            "category" => "exists:categorys,id",
            "buying_price" => "required|numeric",
            "selling_price" => "required|numeric",
            "discount_percentage" => "required|numeric|max:100",
            "discount" => "required|numeric",
            "status" => "required|in:Tersedia,Tidak Tersedia",
            "product_stock" => "required"
        ];

        $td["fieldsmessages"] = [
            "required" => ":attribute harus diisi!!",
            "min" => ":attribute minimal :min karakter!!",
            "max" => ":attribute maksimal :max karakter!!",
            "in" => "Tidak ada dalam pilihan :attribute!!",
            "exists" => "Tidak ada dalam :attribute!!",
            "date_format" => "Format tidak sesuai di :attribute!!"
        ];

        $td["fieldsrules_product_stock"] = [
            "warehouse" => "required|exists:warehouses,id",
            "stock" => "required|numeric"
        ];

        $td["fieldsmessages_product_stock"] = [
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
        
        return view("product.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["product.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("product.create", ["page_data" => $page_data]);
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
        $rules_product_stock = $page_data["fieldsrules_product_stock"];
        $requests_product_stock = json_decode($request->product_stock, true);
        foreach($requests_product_stock as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_product_stock"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_product_stock, $ct_messages);
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $id = Product::create([
                "product_name"=> $request->product_name,
                "product_photo"=> $request->product_photo,
                "company_id"=> Auth::user()->company_id,
                "produce_code"=> $request->produce_code,
                "uom"=> $request->uom,
                "uom_label"=> $request->uom_label,
                "category"=> $request->category,
                "category_label"=> $request->category_label,
                "buying_price"=> $request->buying_price,
                "selling_price"=> $request->selling_price,
                "discount_percentage"=> $request->discount_percentage,
                "discount"=> $request->discount,
                "status"=> $request->status,
                "status_label"=> $request->status_label,
                "user_creator_id"=> Auth::user()->id
            ])->id;

            foreach($requests_product_stock as $ct_request){
                Product_stock::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "company_id"=> Auth::user()->company_id,
                    "warehouse"=> $ct_request["warehouse"],
                    "warehouse_label"=> $ct_request["warehouse_label"],
                    "stock"=> $ct_request["stock"],
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
    public function show(Product $product)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["product.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $product->id;
        return view("product.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Product $product)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["product.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $product->id;
        return view("product.create", ["page_data" => $page_data]);
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
        $rules_product_stock = $page_data["fieldsrules_product_stock"];
        $requests_product_stock = json_decode($request->product_stock, true);
        foreach($requests_product_stock as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_product_stock"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_product_stock, $ct_messages);
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            Product::where("id", $id)->update([
                "product_name"=> $request->product_name,
                "product_photo"=> $request->product_photo,
                "company_id"=> Auth::user()->company_id,
                "produce_code"=> $request->produce_code,
                "uom"=> $request->uom,
                "uom_label"=> $request->uom_label,
                "category"=> $request->category,
                "category_label"=> $request->category_label,
                "buying_price"=> $request->buying_price,
                "selling_price"=> $request->selling_price,
                "discount_percentage"=> $request->discount_percentage,
                "discount"=> $request->discount,
                "status"=> $request->status,
                "status_label"=> $request->status_label,
                "user_updater_id"=> Auth::user()->id
            ]);

            $new_menu_field_ids = array();
            foreach($requests_product_stock as $ct_request){
                if(isset($ct_request["id"])){
                    Product_stock::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                "company_id"=> Auth::user()->company_id,
                    "warehouse"=> $ct_request["warehouse"],
                    "warehouse_label"=> $ct_request["warehouse_label"],
                    "stock"=> $ct_request["stock"],
                        "user_updater_id" => Auth::user()->id
                    ]);
                }else{
                    $idct = Product_stock::create([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                    "company_id"=> Auth::user()->company_id,
                    "warehouse"=> $ct_request["warehouse"],
                    "warehouse_label"=> $ct_request["warehouse_label"],
                    "stock"=> $ct_request["stock"],
                        "user_creator_id" => Auth::user()->id
                    ])->id;
                    array_push($new_menu_field_ids, $idct);
                }
            }

            foreach(Product_stock::whereParentId($id)->get() as $ch){
                    $is_still_exist = false;
                    foreach($requests_product_stock as $ct_request){
                        if($ch->id == $ct_request["id"] || in_array($ch->id, $new_menu_field_ids)){
                            $is_still_exist = true;
                        }
                    }
                    if(!$is_still_exist){
                        Product_stock::whereId($ch->id)->delete();
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
            $product = Product::whereId($request->id)->first();
            if(!$product){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Product::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "product_name", "produce_code", "uom_label", "category_label", "id");
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
        foreach(Product::where(function($q) use ($keyword) {
            $q->where("product_name", "LIKE", "%" . $keyword. "%")->orWhere("produce_code", "LIKE", "%" . $keyword. "%")->orWhere("uom_label", "LIKE", "%" . $keyword. "%")->orWhere("category_label", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "product_name", "produce_code", "uom_label", "category_label"]) as $product){
            $no = $no+1;
            $act = '
            <a href="/product/'.$product->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/product/'.$product->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($product->id, $product->product_name, $product->produce_code, $product->uom_label, $product->category_label, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Product::get()->count(),
            "recordsFiltered" => intval(Product::where(function($q) use ($keyword) {
                $q->where("product_name", "LIKE", "%" . $keyword. "%")->orWhere("produce_code", "LIKE", "%" . $keyword. "%")->orWhere("uom_label", "LIKE", "%" . $keyword. "%")->orWhere("category_label", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $product = Product::whereId($request->id)->first();
            if(!$product){
                abort(404, "Data not found");
            }

            $product_stocks = Product_stock::whereParentId($request->id)->get();

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "product_stock" => $product_stocks,
                    "product" => $product
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
            if($request->field == "uom"){
                $lists = Uom::where(function($q) use ($request) {
                    $q->where("uom_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("uom_name as text")]);
                $count = Uom::count();
            }elseif($request->field == "category"){
                $lists = Category::where(function($q) use ($request) {
                    $q->where("category_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("category_name as text")]);
                $count = Category::count();
            }elseif($request->field == "warehouse"){
                $lists = Warehouse::where(function($q) use ($request) {
                    $q->where("warehouse_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("warehouse_name as text")]);
                $count = Warehouse::count();
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

    public function storeUploadFile(Request $request){
        if($request->ajax()){
            $input = $request->all();
            $input['file'] = $request->menname.time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path($request->menname), $input['file']);

            return response()->json([
                "status" => 200,
                "message" => "Upload successfully",
                "filename" => $input['file']
            ]);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
