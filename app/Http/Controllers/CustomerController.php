<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Segment_level;
use App\Models\Member_level;

class CustomerController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Customer",
            "page_data_urlname" => "customer",
            "fields" => [
                "customer_name" => "text",
                "photo_profile" => "upload",
                "id_card_number" => "text",
                "first_name" => "text",
                "last_name" => "text",
                "email" => "text",
                "phone_1" => "text",
                "phone_2" => "text",
                "segment_level" => "link",
                "member_level" => "link"
            ],
            "fieldschildtable" => [
            ],
            "fieldlink" => [
                "segment_level" => "segment_levels",
                "member_level" => "member_levels"
            ]
        ];

        $td["fieldsrules"] = [
            "customer_name" => "required|min:2|max:255",
            "id_card_number" => "required|max:75",
            "first_name" => "max:255",
            "last_name" => "required|min:2|max:255",
            "email" => "max:255",
            "phone_1" => "required",
            "segment_level" => "required|exists:segment_levels,id",
            "member_level" => "required|exists:member_levels,id"
        ];

        $td["fieldsmessages"] = [
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
        
        return view("customer.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["customer.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("customer.create", ["page_data" => $page_data]);
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
        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            $id = Customer::create([
                "customer_name"=> $request->customer_name,
                "photo_profile"=> $request->photo_profile,
                "company_id"=> Auth::user()->company_id,
                "id_card_number"=> $request->id_card_number,
                "first_name"=> $request->first_name,
                "last_name"=> $request->last_name,
                "email"=> $request->email,
                "phone_1"=> $request->phone_1,
                "phone_2"=> $request->phone_2,
                "segment_level"=> $request->segment_level,
                "segment_level_label"=> $request->segment_level_label,
                "member_level"=> $request->member_level,
                "member_level_label"=> $request->member_level_label,
                "user_creator_id"=> Auth::user()->id
            ])->id;

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
    public function show(Customer $customer)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["customer.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $customer->id;
        return view("customer.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Customer $customer)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["customer.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $customer->id;
        return view("customer.create", ["page_data" => $page_data]);
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
        $rules = $page_data["fieldsrules"];
        $messages = $page_data["fieldsmessages"];
        if($request->validate($rules, $messages)){
            Customer::where("id", $id)->update([
                "customer_name"=> $request->customer_name,
                "photo_profile"=> $request->photo_profile,
                "company_id"=> Auth::user()->company_id,
                "id_card_number"=> $request->id_card_number,
                "first_name"=> $request->first_name,
                "last_name"=> $request->last_name,
                "email"=> $request->email,
                "phone_1"=> $request->phone_1,
                "phone_2"=> $request->phone_2,
                "segment_level"=> $request->segment_level,
                "segment_level_label"=> $request->segment_level_label,
                "member_level"=> $request->member_level,
                "member_level_label"=> $request->member_level_label,
                "user_updater_id"=> Auth::user()->id
            ]);

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
            $customer = Customer::whereId($request->id)->first();
            if(!$customer){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Customer::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "customer_name", "last_name", "email", "phone_1", "segment_level_label", "member_level_label", "id");
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
        foreach(Customer::where(function($q) use ($keyword) {
            $q->where("customer_name", "LIKE", "%" . $keyword. "%")->orWhere("last_name", "LIKE", "%" . $keyword. "%")->orWhere("email", "LIKE", "%" . $keyword. "%")->orWhere("phone_1", "LIKE", "%" . $keyword. "%")->orWhere("segment_level_label", "LIKE", "%" . $keyword. "%")->orWhere("member_level_label", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "customer_name", "last_name", "email", "phone_1", "segment_level_label", "member_level_label"]) as $customer){
            $no = $no+1;
            $act = '
            <a href="/customer/'.$customer->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/customer/'.$customer->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($customer->id, $customer->customer_name, $customer->last_name, $customer->email, $customer->phone_1, $customer->segment_level_label, $customer->member_level_label, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Customer::get()->count(),
            "recordsFiltered" => intval(Customer::where(function($q) use ($keyword) {
                $q->where("customer_name", "LIKE", "%" . $keyword. "%")->orWhere("last_name", "LIKE", "%" . $keyword. "%")->orWhere("email", "LIKE", "%" . $keyword. "%")->orWhere("phone_1", "LIKE", "%" . $keyword. "%")->orWhere("segment_level_label", "LIKE", "%" . $keyword. "%")->orWhere("member_level_label", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $customer = Customer::whereId($request->id)->first();
            if(!$customer){
                abort(404, "Data not found");
            }


            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "customer" => $customer
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
            if($request->field == "segment_level"){
                $lists = Segment_level::where(function($q) use ($request) {
                    $q->where("segment_level_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("segment_level_name as text")]);
                $count = Segment_level::count();
            }elseif($request->field == "member_level"){
                $lists = Member_level::where(function($q) use ($request) {
                    $q->where("member_level_name", "LIKE", "%" . $request->term. "%");
                })->orderBy("id")->skip($offset)->take($resultCount)->get(["id", DB::raw("member_level_name as text")]);
                $count = Member_level::count();
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

    public function getCustomerDiscount(Request $request){
        if($request->ajax()){
            $customer = Customer::whereId($request->customer)->select(["id", "segment_level", "member_level"])->first();
            
            if(!$customer){
                abort(404, "Data not found");
            }

            $member_level = Member_level::whereId($customer->member_level)->select(["id", "member_level_name", "discount_percentage", "discount"])->first();

            $segment_level = Segment_level::whereId($customer->segment_level)->select(["id", "segment_level_name", "discount_percentage", "discount"])->first();

            //discount priority is getting from discount percentage
            if($member_level->discount_percentage > 0 || $segment_level->discount_percentage > 0){
                $customer->is_discount_percentage = true;
                if($member_level->discount_percentage > $segment_level->discount_percentage ){
                    $customer->discount_from = "Member";
                    $customer->level_name = $member_level->member_level_name;
                    $customer->discount_percentage = $member_level->discount_percentage;
                }else{
                    $customer->discount_from = "Segment";
                    $customer->level_name = $segment_level->segment_level_name;
                    $customer->discount_percentage = $segment_level->discount_percentage;
                }
            }else{
                $customer->is_discount_percentage = false;
                if($member_level->discount_percentage > $segment_level->discount_percentage ){
                    $customer->discount_from = "Member";
                    $customer->level_name = $member_level->member_level_name;
                    $customer->discount = $member_level->discount;
                }else{
                    $customer->discount_from = "Segment";
                    $customer->level_name = $segment_level->segment_level_name;
                    $customer->discount = $segment_level->discount;
                }
            }

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "customer" => $customer
                ]
            );

            return response()->json($results);
        }
    }
}
