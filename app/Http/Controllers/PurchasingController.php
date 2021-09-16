<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Purchasing;
use App\Models\Supplier;
use App\Models\Purchasing_detail;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Product_stock;
use App\Models\Payment_detail;

class PurchasingController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Purchasing",
            "page_data_urlname" => "purchasing",
            "fields" => [
                "purchasing_name" => "text",
                "purchasing_datetime" => "datetime",
                "buying_datetime" => "datetime",
                "supplier" => "link",
                "ct1_purchasing_detail" => "childtable",
                "purchasing_detail_total" => "float",
                "purchasing_discount_percentage" => "float",
                "purchasing_discount_total" => "float",
                "purchasing_total" => "float",
                "is_paynow" => "checkbox",
                "buying_total" => "float",
                "change_total" => "float",
                "purchasing_status" => "text",
                "ct2_payment_detail" => "childtable"
            ],
            "fieldschildtable" => [
                "ct1_purchasing_detail" => [
                    "product" => "link",
                    "purchasing_price" => "float",
                    "quantity" => "float",
                    "discount_percentage" => "float",
                    "discount_total" => "float",
                    "total" => "float",
                    "warehouse" => "link"
                ],
                "ct2_payment_detail" => [
                    "payment_type" => "hidden",
                    "paying_method" => "select",
                    "paying" => "float",
                    "payment_notes" => "textarea"
                ]
            ],
            "fieldsoptions" => [
                "paying_method" => [
                    ["name" => "cash", "label" => "Tunai"],
                    ["name" => "bank_transfer", "label" => "Transfer Bank"],
                    ["name" => "credit_card", "label" => "Kartu Kredit"]
                ]
            ],
            "fieldlink" => [
                "supplier" => "suppliers",
                "product" => "products",
                "warehouse" => "warehouses"
            ]
        ];

        $paying_method_list = "cash,bank_transfer,credit_card";

        $td["fieldsrules"] = [
            "purchasing_datetime" => "required|date_format:d/m/Y H:i",
            "buying_datetime" => "required|date_format:d/m/Y H:i",
            "supplier" => "exists:suppliers,id",
            "ct1_purchasing_detail" => "required",
            "purchasing_detail_total" => "required|numeric",
            "purchasing_discount_percentage" => "required|numeric|max:100",
            "purchasing_discount_total" => "required|numeric",
            "purchasing_total" => "required|numeric",
            "buying_total" => "required|numeric",
            "change_total" => "required|numeric"
        ];

        $td["fieldsmessages"] = [
            "required" => ":attribute harus diisi!!",
            "min" => ":attribute minimal :min karakter!!",
            "max" => ":attribute maksimal :max karakter!!",
            "in" => "Tidak ada dalam pilihan :attribute!!",
            "exists" => "Tidak ada dalam :attribute!!",
            "date_format" => "Format tidak sesuai di :attribute!!"
        ];

        $td["fieldsrules_ct1_purchasing_detail"] = [
            "purchasing_price" => "required|numeric",
            "quantity" => "required|numeric",
            "discount_percentage" => "numeric|max:100",
            "discount_total" => "numeric",
            "total" => "required|numeric",
            "warehouse" => "required|exists:warehouses,id"
        ];

        $td["fieldsmessages_ct1_purchasing_detail"] = [
            "required" => ":attribute harus diisi!!",
            "min" => ":attribute minimal :min karakter!!",
            "max" => ":attribute maksimal :max karakter!!",
            "in" => "Tidak ada dalam pilihan :attribute!!",
            "exists" => "Tidak ada dalam :attribute!!",
            "date_format" => "Format tidak sesuai di :attribute!!"
        ];

        $td["fieldsrules_ct2_payment_detail"] = [
            "paying_method" => "required|in:cash,bank_transfer,credit_card",
            "payment_notes" => "max:255",
            "paying" => "required|numeric"
        ];

        $td["fieldsmessages_ct2_payment_detail"] = [
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
        
        return view("purchasing.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["purchasing.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("purchasing.create", ["page_data" => $page_data]);
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
        $rules_ct1_purchasing_detail = $page_data["fieldsrules_ct1_purchasing_detail"];
        $requests_ct1_purchasing_detail = json_decode($request->ct1_purchasing_detail, true);
        $price_total = 0;
        foreach($requests_ct1_purchasing_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_purchasing_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_purchasing_detail, $ct_messages);
            $price_total += $ct_request["total"];
        }

        $rules_ct2_payment_detail = $page_data["fieldsrules_ct2_payment_detail"];
        $requests_ct2_payment_detail = json_decode($request->ct2_payment_detail, true);
        $buying_total = 0;
        foreach($requests_ct2_payment_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct2_payment_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct2_payment_detail, $ct_messages);
            $buying_total += $ct_request["paying"];
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $purchasing_status = "Belum Lunas";
            if($price_total <= $buying_total){
                $purchasing_status = "Lunas";
            }
            $id = Purchasing::create([
                "purchasing_name"=> "PURC-XXXXX",
                "company_id"=> Auth::user()->company_id,
                "purchasing_datetime"=> $request->purchasing_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->purchasing_datetime)->format('Y-m-d H:i'):null,
                "buying_datetime"=> $request->buying_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->buying_datetime)->format('Y-m-d H:i'):null,
                "supplier"=> $request->supplier,
                "supplier_label"=> $request->supplier_label,
                "purchasing_detail_total"=> $request->purchasing_detail_total,
                "purchasing_discount_percentage"=> $request->purchasing_discount_percentage,
                "purchasing_discount_total"=> $request->purchasing_discount_total,
                "purchasing_total"=> $request->purchasing_total,
                "is_paynow"=> isset($request->is_paynow)?$request->is_paynow:null,
                "buying_total"=> $request->buying_total,
                "change_total"=> $request->change_total,
                "purchasing_status"=> $purchasing_status,
                "user_creator_id"=> Auth::user()->id
            ])->id;

            $nol = "";
            for($i = 0; $i < 5-strlen("".$id); $i++){
                $nol .= "0";
            }
            
            $purchasing_name = "PURC-".$nol.$id;
            purchasing::whereId($id)->update([
                "purchasing_name"=> $purchasing_name
            ]);

            foreach($requests_ct1_purchasing_detail as $ct_request){
                Purchasing_detail::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "product"=> $ct_request["product"],
                    "product_label"=> $ct_request["product_label"],
                    "purchasing_price"=> $ct_request["purchasing_price"],
                    "quantity"=> $ct_request["quantity"],
                    "discount_percentage"=> $ct_request["discount_percentage"],
                    "discount_total"=> $ct_request["discount_total"],
                    "total"=> $ct_request["total"],
                    "warehouse"=> $ct_request["warehouse"],
                    "warehouse_label"=> $ct_request["warehouse_label"],
                    "user_creator_id" => Auth::user()->id
                ]);

                if($ct_request["product"] && $ct_request["warehouse"]){
                    Product_stock::whereParentId($ct_request["product"])->where("warehouse", $ct_request["warehouse"])->increment("stock", $ct_request["quantity"]);
                }
            }

            foreach($requests_ct2_payment_detail as $ct_request){
                Payment_detail::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "payment_type"=> "purchasing",
                    "paying_method"=> $ct_request["paying_method"],
                    "paying_method_label"=> $ct_request["paying_method_label"],
                    "payment_notes"=> $ct_request["payment_notes"],
                    "paying"=> $ct_request["paying"],
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
    public function show(Purchasing $purchasing)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["purchasing.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $purchasing->id;
        return view("purchasing.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Purchasing $purchasing)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["purchasing.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $purchasing->id;
        return view("purchasing.create", ["page_data" => $page_data]);
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
        $rules_ct1_purchasing_detail = $page_data["fieldsrules_ct1_purchasing_detail"];
        $requests_ct1_purchasing_detail = json_decode($request->ct1_purchasing_detail, true);
        $price_total = 0;
        foreach($requests_ct1_purchasing_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_purchasing_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_purchasing_detail, $ct_messages);
            $price_total += $ct_request["total"];
        }

        $rules_ct2_payment_detail = $page_data["fieldsrules_ct2_payment_detail"];
        $requests_ct2_payment_detail = json_decode($request->ct2_payment_detail, true);
        $buying_total = 0;
        foreach($requests_ct2_payment_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct2_payment_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct2_payment_detail, $ct_messages);
            $buying_total += $ct_request["paying"];
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $purchasing_status = "Belum Lunas";
            if($price_total <= $buying_total){
                $purchasing_status = "Lunas";
            }
            Purchasing::where("id", $id)->update([
                "company_id"=> Auth::user()->company_id,
                "purchasing_datetime"=> $request->purchasing_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->purchasing_datetime)->format('Y-m-d H:i'):null,
                "buying_datetime"=> $request->buying_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->buying_datetime)->format('Y-m-d H:i'):null,
                "supplier"=> $request->supplier,
                "supplier_label"=> $request->supplier_label,
                "purchasing_detail_total"=> $request->purchasing_detail_total,
                "purchasing_discount_percentage"=> $request->purchasing_discount_percentage,
                "purchasing_discount_total"=> $request->purchasing_discount_total,
                "purchasing_total"=> $request->purchasing_total,
                "is_paynow"=> isset($request->is_paynow)?$request->is_paynow:null,
                "buying_total"=> $request->buying_total,
                "change_total"=> $request->change_total,
                "purchasing_status"=> $purchasing_status,
                "user_updater_id"=> Auth::user()->id
            ]);

            $new_menu_field_ids = array();
            foreach($requests_ct1_purchasing_detail as $ct_request){
                if(isset($ct_request["id"])){
                    Purchasing_detail::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "product"=> $ct_request["product"],
                        "product_label"=> $ct_request["product_label"],
                        "purchasing_price"=> $ct_request["purchasing_price"],
                        "quantity"=> $ct_request["quantity"],
                        "discount_percentage"=> $ct_request["discount_percentage"],
                        "discount_total"=> $ct_request["discount_total"],
                        "total"=> $ct_request["total"],
                        "warehouse"=> $ct_request["warehouse"],
                        "warehouse_label"=> $ct_request["warehouse_label"],
                        "user_updater_id" => Auth::user()->id
                    ]);
                }else{
                    $idct = Purchasing_detail::create([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "product"=> $ct_request["product"],
                        "product_label"=> $ct_request["product_label"],
                        "purchasing_price"=> $ct_request["purchasing_price"],
                        "quantity"=> $ct_request["quantity"],
                        "discount_percentage"=> $ct_request["discount_percentage"],
                        "discount_total"=> $ct_request["discount_total"],
                        "total"=> $ct_request["total"],
                        "warehouse"=> $ct_request["warehouse"],
                        "warehouse_label"=> $ct_request["warehouse_label"],
                        "user_creator_id" => Auth::user()->id
                    ])->id;
                    array_push($new_menu_field_ids, $idct);
                }
            }

            foreach(Purchasing_detail::whereParentId($id)->get() as $ch){
                $is_still_exist = false;
                foreach($requests_ct1_purchasing_detail as $ct_request){
                    if($ch->id == $ct_request["id"] || in_array($ch->id, $new_menu_field_ids)){
                        $is_still_exist = true;
                    }
                }
                if(!$is_still_exist){
                    Purchasing_detail::whereId($ch->id)->delete();
                }
            }

            $new_menu_field_ids = array();
            foreach($requests_ct2_payment_detail as $ct_request){
                if(isset($ct_request["id"])){
                    Payment_detail::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "payment_type"=> "purchasing",
                        "paying_method"=> $ct_request["paying_method"],
                        "paying_method_label"=> $ct_request["paying_method_label"],
                        "payment_notes"=> $ct_request["payment_notes"],
                        "paying"=> $ct_request["paying"],
                        "user_updater_id" => Auth::user()->id
                    ]);
                }else{
                    $idct = Payment_detail::create([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "payment_type"=> "purchasing",
                        "paying_method"=> $ct_request["paying_method"],
                        "paying_method_label"=> $ct_request["paying_method_label"],
                        "payment_notes"=> $ct_request["payment_notes"],
                        "paying"=> $ct_request["paying"],
                        "user_creator_id" => Auth::user()->id
                    ])->id;
                    array_push($new_menu_field_ids, $idct);
                }
            }

            foreach(Payment_detail::whereParentId($id)->get() as $ch){
                $is_still_exist = false;
                foreach($requests_ct2_payment_detail as $ct_request){
                    if($ch->id == $ct_request["id"] || in_array($ch->id, $new_menu_field_ids)){
                        $is_still_exist = true;
                    }
                }
                if(!$is_still_exist){
                    Payment_detail::whereId($ch->id)->delete();
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
            $purchasing = Purchasing::whereId($request->id)->first();
            if(!$purchasing){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Purchasing::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "purchasing_name", "purchasing_datetime", "supplier_label", "purchasing_total", "buying_total", "change_total", "id");
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
        foreach(Purchasing::where(function($q) use ($keyword) {
            $q->where("purchasing_name", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_datetime", "LIKE", "%" . $keyword. "%")->orWhere("supplier_label", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_status", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_total", "LIKE", "%" . $keyword. "%")->orWhere("buying_total", "LIKE", "%" . $keyword. "%")->orWhere("change_total", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "purchasing_name", "purchasing_status", DB::raw("date_format(purchasing_datetime, '%d/%m/%Y %h:%i') as purchasing_datetime"), "supplier_label", "purchasing_total", "buying_total", "change_total"]) as $purchasing){
            $no = $no+1;
            $act = '
            <a href="/purchasing/'.$purchasing->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/purchasing/'.$purchasing->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($purchasing->id, $purchasing->purchasing_name, $purchasing->purchasing_status, $purchasing->purchasing_datetime, $purchasing->supplier_label, $purchasing->purchasing_total, $purchasing->buying_total, $purchasing->change_total, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Purchasing::get()->count(),
            "recordsFiltered" => intval(Purchasing::where(function($q) use ($keyword) {
                $q->where("purchasing_name", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_datetime", "LIKE", "%" . $keyword. "%")->orWhere("supplier_label", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_status", "LIKE", "%" . $keyword. "%")->orWhere("purchasing_total", "LIKE", "%" . $keyword. "%")->orWhere("buying_total", "LIKE", "%" . $keyword. "%")->orWhere("change_total", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $purchasing = Purchasing::whereId($request->id)->first();
            if(!$purchasing){
                abort(404, "Data not found");
            }

            $purchasing->purchasing_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $purchasing->purchasing_datetime)->format('d/m/Y H:i');
            $purchasing->buying_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $purchasing->buying_datetime)->format('d/m/Y H:i');
            $ct1_purchasing_details = Purchasing_detail::whereParentId($request->id)->get();
            $ct2_payment_details = Payment_detail::whereParentId($request->id)->get();

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "ct1_purchasing_detail" => $ct1_purchasing_details,
                    "ct2_payment_detail" => $ct2_payment_details,
                    "purchasing" => $purchasing
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
            if($request->field == "supplier"){
                $lists = Supplier::where(function($q) use ($request) {
                    $q->where("supplier_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("supplier_name as text")]);
                $count = Supplier::count();
            }elseif($request->field == "product"){
                $lists = Product::where(function($q) use ($request) {
                    $q->where("product_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->select(["id", DB::raw("product_name as text")])->get();
                $count = Product::count();
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
}
