<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Selling;
use App\Models\Customer;
use App\Models\Selling_detail;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Product_stock;
use App\Models\Bundle;
use App\Models\Payment_detail;

class SellingController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Selling",
            "page_data_urlname" => "selling",
            "fields" => [
                "selling_name" => "text",
                "selling_datetime" => "datetime",
                "paying_datetime" => "datetime",
                "customer" => "link",
                "ct1_selling_detail" => "childtable",
                "selling_detail_total" => "float",
                "selling_discount_percentage" => "float",
                "selling_discount_total" => "float",
                "selling_total" => "float",
                "is_paynow" => "checkbox",
                "paying_total" => "float",
                "change_total" => "float",
                "selling_status" => "text",
                "ct2_payment_detail" => "childtable"
            ],
            "fieldschildtable" => [
                "ct1_selling_detail" => [
                    "product_or_bundle" => "link",
                    "is_bundle" => "hidden",
                    "product" => "hidden",
                    "product_label" => "hidden",
                    "bundle" => "hidden",
                    "bundle_label" => "hidden",
                    "selling_price" => "float",
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
                "customer" => "customers",
                "product_or_bundle" => "products",
                "warehouse" => "warehouses"
            ]
        ];

        $paying_method_list = "cash,bank_transfer,credit_card";

        $td["fieldsrules"] = [
            "selling_datetime" => "required|date_format:d/m/Y H:i",
            "paying_datetime" => "required|date_format:d/m/Y H:i",
            "customer" => "exists:customers,id",
            "ct1_selling_detail" => "required",
            "selling_detail_total" => "required|numeric",
            "selling_discount_percentage" => "required|numeric|max:100",
            "selling_discount_total" => "required|numeric",
            "selling_total" => "required|numeric",
            "paying_total" => "required|numeric",
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

        $td["fieldsrules_ct1_selling_detail"] = [
            "selling_price" => "required|numeric",
            "quantity" => "required|numeric",
            "discount_percentage" => "numeric|max:100",
            "discount_total" => "numeric",
            "total" => "required|numeric",
            "warehouse" => "required|exists:warehouses,id"
        ];

        $td["fieldsmessages_ct1_selling_detail"] = [
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
        
        return view("selling.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["selling.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("selling.create", ["page_data" => $page_data]);
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
        $rules_ct1_selling_detail = $page_data["fieldsrules_ct1_selling_detail"];
        $requests_ct1_selling_detail = json_decode($request->ct1_selling_detail, true);
        $price_total = 0;
        foreach($requests_ct1_selling_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_selling_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_selling_detail, $ct_messages);
            $price_total += $ct_request["total"];
        }

        $rules_ct2_payment_detail = $page_data["fieldsrules_ct2_payment_detail"];
        $requests_ct2_payment_detail = json_decode($request->ct2_payment_detail, true);
        $paying_total = 0;
        foreach($requests_ct2_payment_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct2_payment_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct2_payment_detail, $ct_messages);
            $paying_total += $ct_request["paying"];
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $selling_status = "Belum Lunas";
            if($price_total <= $paying_total){
                $selling_status = "Lunas";
            }
            $id = Selling::create([
                "selling_name"=> "SELL-XXXXX",
                "company_id"=> Auth::user()->company_id,
                "selling_datetime"=> $request->selling_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->selling_datetime)->format('Y-m-d H:i'):null,
                "paying_datetime"=> $request->paying_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->paying_datetime)->format('Y-m-d H:i'):null,
                "customer"=> $request->customer,
                "customer_label"=> $request->customer_label,
                "selling_detail_total"=> $request->selling_detail_total,
                "selling_discount_percentage"=> $request->selling_discount_percentage,
                "selling_discount_total"=> $request->selling_discount_total,
                "selling_total"=> $request->selling_total,
                "is_paynow"=> isset($request->is_paynow)?$request->is_paynow:null,
                "paying_total"=> $request->paying_total,
                "change_total"=> $request->change_total,
                "selling_status"=> $selling_status,
                "user_creator_id"=> Auth::user()->id
            ])->id;

            $nol = "";
            for($i = 0; $i < 5-strlen("".$id); $i++){
                $nol .= "0";
            }
            
            $selling_name = "SELL-".$nol.$id;
            Selling::whereId($id)->update([
                "selling_name"=> $selling_name
            ]);

            foreach($requests_ct1_selling_detail as $ct_request){
                Selling_detail::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "product_or_bundle"=> $ct_request["product_or_bundle"],
                    "product_or_bundle_label"=> $ct_request["product_or_bundle_label"],
                    "selling_price"=> $ct_request["selling_price"],
                    "quantity"=> $ct_request["quantity"],
                    "discount_percentage"=> $ct_request["discount_percentage"],
                    "discount_total"=> $ct_request["discount_total"],
                    "total"=> $ct_request["total"],
                    "warehouse"=> $ct_request["warehouse"],
                    "warehouse_label"=> $ct_request["warehouse_label"],
                    "is_bundle"=> $ct_request["is_bundle"],
                    "product"=> $ct_request["product"],
                    "product_label"=> $ct_request["product_label"],
                    "bundle"=> $ct_request["bundle"],
                    "bundle_label"=> $ct_request["bundle_label"],
                    "user_creator_id" => Auth::user()->id
                ]);

                if($ct_request["is_bundle"] == "yes"){
                    if($ct_request["bundle"]){
                        foreach(Bundle_detail::whereParentId($ct_request["bundle"])->get() as $bundles){
                            $product_stock = Product_stock::whereParentId($bundles->product)->where("warehouse", $ct_request["warehouse"])->decrement("stock", $ct_request["quantity"]*$bundles->quantity);
                            
                        }
                    }
                }else{
                    Product_stock::whereParentId($ct_request["product"])->where("warehouse", $ct_request["warehouse"])->decrement("stock", $ct_request["quantity"]);
                }
            }

            foreach($requests_ct2_payment_detail as $ct_request){
                Payment_detail::create([
                    "no_seq" => $ct_request["no_seq"],
                    "parent_id" => $id,
                    "payment_type"=> "selling",
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
    public function show(Selling $selling)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["selling.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $selling->id;
        return view("selling.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Selling $selling)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["selling.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $selling->id;
        return view("selling.create", ["page_data" => $page_data]);
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
        $rules_ct1_selling_detail = $page_data["fieldsrules_ct1_selling_detail"];
        $requests_ct1_selling_detail = json_decode($request->ct1_selling_detail, true);
        $price_total = 0;
        foreach($requests_ct1_selling_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct1_selling_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct1_selling_detail, $ct_messages);
            $price_total += $ct_request["total"];
        }

        $rules_ct2_payment_detail = $page_data["fieldsrules_ct2_payment_detail"];
        $requests_ct2_payment_detail = json_decode($request->ct2_payment_detail, true);
        $paying_total = 0;
        foreach($requests_ct2_payment_detail as $ct_request){
            $child_tb_request = new \Illuminate\Http\Request();
            $child_tb_request->replace($ct_request);
            $ct_messages = array();
            foreach($page_data["fieldsmessages_ct2_payment_detail"] as $key => $value){
                $ct_messages[$key] = "No ".$ct_request["no_seq"]." ".$value;
            }
            $child_tb_request->validate($rules_ct2_payment_detail, $ct_messages);
            $paying_total += $ct_request["paying"];
        }

        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $selling_status = "Belum Lunas";
            if($price_total <= $paying_total){
                $selling_status = "Lunas";
            }
            Selling::where("id", $id)->update([
                "company_id"=> Auth::user()->company_id,
                "selling_datetime"=> $request->selling_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->selling_datetime)->format('Y-m-d H:i'):null,
                "paying_datetime"=> $request->paying_datetime?\Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->paying_datetime)->format('Y-m-d H:i'):null,
                "customer"=> $request->customer,
                "customer_label"=> $request->customer_label,
                "selling_detail_total"=> $request->selling_detail_total,
                "selling_discount_percentage"=> $request->selling_discount_percentage,
                "selling_discount_total"=> $request->selling_discount_total,
                "selling_total"=> $request->selling_total,
                "is_paynow"=> isset($request->is_paynow)?$request->is_paynow:null,
                "paying_total"=> $request->paying_total,
                "change_total"=> $request->change_total,
                "selling_status"=> $selling_status,
                "user_updater_id"=> Auth::user()->id
            ]);

            $new_menu_field_ids = array();
            foreach($requests_ct1_selling_detail as $ct_request){
                if(isset($ct_request["id"])){
                    Selling_detail::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "product_or_bundle"=> $ct_request["product_or_bundle"],
                        "product_or_bundle_label"=> $ct_request["product_or_bundle_label"],
                        "selling_price"=> $ct_request["selling_price"],
                        "quantity"=> $ct_request["quantity"],
                        "discount_percentage"=> $ct_request["discount_percentage"],
                        "discount_total"=> $ct_request["discount_total"],
                        "total"=> $ct_request["total"],
                        "warehouse"=> $ct_request["warehouse"],
                        "warehouse_label"=> $ct_request["warehouse_label"],
                        "is_bundle"=> $ct_request["is_bundle"],
                        "product"=> $ct_request["product"],
                        "product_label"=> $ct_request["product_label"],
                        "bundle"=> $ct_request["bundle"],
                        "bundle_label"=> $ct_request["bundle_label"],
                        "user_updater_id" => Auth::user()->id
                    ]);
                }else{
                    $idct = Selling_detail::create([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "product_or_bundle"=> $ct_request["product_or_bundle"],
                        "product_or_bundle_label"=> $ct_request["product_or_bundle_label"],
                        "selling_price"=> $ct_request["selling_price"],
                        "quantity"=> $ct_request["quantity"],
                        "discount_percentage"=> $ct_request["discount_percentage"],
                        "discount_total"=> $ct_request["discount_total"],
                        "total"=> $ct_request["total"],
                        "warehouse"=> $ct_request["warehouse"],
                        "warehouse_label"=> $ct_request["warehouse_label"],
                        "is_bundle"=> $ct_request["is_bundle"],
                        "product"=> $ct_request["product"],
                        "product_label"=> $ct_request["product_label"],
                        "bundle"=> $ct_request["bundle"],
                        "bundle_label"=> $ct_request["bundle_label"],
                        "user_creator_id" => Auth::user()->id
                    ])->id;
                    array_push($new_menu_field_ids, $idct);
                }
            }

            foreach(Selling_detail::whereParentId($id)->get() as $ch){
                $is_still_exist = false;
                foreach($requests_ct1_selling_detail as $ct_request){
                    if($ch->id == $ct_request["id"] || in_array($ch->id, $new_menu_field_ids)){
                        $is_still_exist = true;
                    }
                }
                if(!$is_still_exist){
                    Selling_detail::whereId($ch->id)->delete();
                }
            }

            $new_menu_field_ids = array();
            foreach($requests_ct2_payment_detail as $ct_request){
                if(isset($ct_request["id"])){
                    Payment_detail::where("id", $ct_request["id"])->update([
                        "no_seq" => $ct_request["no_seq"],
                        "parent_id" => $id,
                        "payment_type"=> "selling",
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
                        "payment_type"=> "selling",
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
            $selling = Selling::whereId($request->id)->first();
            if(!$selling){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Selling::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "selling_name", "selling_datetime", "customer_label", "selling_total", "paying_total", "change_total", "id");
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
        foreach(Selling::where(function($q) use ($keyword) {
            $q->where("selling_name", "LIKE", "%" . $keyword. "%")->orWhere("selling_datetime", "LIKE", "%" . $keyword. "%")->orWhere("customer_label", "LIKE", "%" . $keyword. "%")->orWhere("selling_status", "LIKE", "%" . $keyword. "%")->orWhere("selling_total", "LIKE", "%" . $keyword. "%")->orWhere("paying_total", "LIKE", "%" . $keyword. "%")->orWhere("change_total", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "selling_name", "selling_status", DB::raw("date_format(selling_datetime, '%d/%m/%Y %h:%i') as selling_datetime"), "customer_label", "selling_total", "paying_total", "change_total"]) as $selling){
            $no = $no+1;
            $act = '
            <a href="/selling/'.$selling->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/selling/'.$selling->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($selling->id, $selling->selling_name, $selling->selling_status, $selling->selling_datetime, $selling->customer_label, $selling->selling_total, $selling->paying_total, $selling->change_total, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Selling::get()->count(),
            "recordsFiltered" => intval(Selling::where(function($q) use ($keyword) {
                $q->where("selling_name", "LIKE", "%" . $keyword. "%")->orWhere("selling_datetime", "LIKE", "%" . $keyword. "%")->orWhere("customer_label", "LIKE", "%" . $keyword. "%")->orWhere("selling_status", "LIKE", "%" . $keyword. "%")->orWhere("selling_total", "LIKE", "%" . $keyword. "%")->orWhere("paying_total", "LIKE", "%" . $keyword. "%")->orWhere("change_total", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $selling = Selling::whereId($request->id)->first();
            if(!$selling){
                abort(404, "Data not found");
            }

            $selling->selling_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selling->selling_datetime)->format('d/m/Y H:i');
            $selling->paying_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selling->paying_datetime)->format('d/m/Y H:i');
            $ct1_selling_details = Selling_detail::whereParentId($request->id)->get();
            $ct2_payment_details = Payment_detail::whereParentId($request->id)->get();

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "ct1_selling_detail" => $ct1_selling_details,
                    "ct2_payment_detail" => $ct2_payment_details,
                    "selling" => $selling
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
            if($request->field == "customer"){
                $lists = Customer::where(function($q) use ($request) {
                    $q->where("customer_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("customer_name as text")]);
                $count = Customer::count();
            }elseif($request->field == "product_or_bundle"){
                $bundles = Bundle::where(function($q) use ($request) {
                    $q->where("bundle_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->select(["id", DB::raw("bundle_name as text"), DB::raw("CONCAT(\"BUN-\", id) as type")]);
                $lists = Product::where(function($q) use ($request) {
                    $q->where("product_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->union($bundles)->select(["id", DB::raw("product_name as text"), DB::raw("CONCAT(\"PRO-\", id) as type")])->get();
                $count = Product::count()+Bundle::count();
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
