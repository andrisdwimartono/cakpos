<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "Pemasok",
            "page_data_urlname" => "supplier",
            "fields" => [
                "supplier_name" => "text",
                "first_name" => "text",
                "last_name" => "text",
                "supplier_company" => "text",
                "email" => "text",
                "phone_1" => "text",
                "phone_2" => "text",
                "photo_profile" => "upload"
            ],
            "fieldschildtable" => [
            ]
        ];

        $td["fieldsrules"] = [
            "supplier_name" => "required|min:2|max:255",
            "first_name" => "max:255",
            "last_name" => "required|min:2|max:255",
            "supplier_company" => "max:255",
            "phone_1" => "required|max:20",
            "phone_2" => "max:20"
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
        
        return view("supplier.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["supplier.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("supplier.create", ["page_data" => $page_data]);
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
            $id = Supplier::create([
                "supplier_name"=> $request->supplier_name,
                "company_id"=> Auth::user()->company_id,
                "first_name"=> $request->first_name,
                "last_name"=> $request->last_name,
                "supplier_company"=> $request->supplier_company,
                "email"=> $request->email,
                "phone_1"=> $request->phone_1,
                "phone_2"=> $request->phone_2,
                "photo_profile"=> $request->photo_profile,
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
    public function show(Supplier $supplier)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["supplier.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $supplier->id;
        return view("supplier.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Supplier $supplier)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["supplier.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $supplier->id;
        return view("supplier.create", ["page_data" => $page_data]);
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
            Supplier::where("id", $id)->update([
                "supplier_name"=> $request->supplier_name,
                "company_id"=> Auth::user()->company_id,
                "first_name"=> $request->first_name,
                "last_name"=> $request->last_name,
                "supplier_company"=> $request->supplier_company,
                "email"=> $request->email,
                "phone_1"=> $request->phone_1,
                "phone_2"=> $request->phone_2,
                "photo_profile"=> $request->photo_profile,
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
            $supplier = Supplier::whereId($request->id)->first();
            if(!$supplier){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(Supplier::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "supplier_name", "last_name", "supplier_company", "email", "phone_1", "id");
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
        foreach(Supplier::where(function($q) use ($keyword) {
            $q->where("supplier_name", "LIKE", "%" . $keyword. "%")->orWhere("last_name", "LIKE", "%" . $keyword. "%")->orWhere("supplier_company", "LIKE", "%" . $keyword. "%")->orWhere("email", "LIKE", "%" . $keyword. "%")->orWhere("phone_1", "LIKE", "%" . $keyword. "%");
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get(["id", "supplier_name", "last_name", "supplier_company", "email", "phone_1"]) as $supplier){
            $no = $no+1;
            $act = '
            <a href="/supplier/'.$supplier->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>

            <a href="/supplier/'.$supplier->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>

            <button type="button" class="btn btn-danger row-delete"> <i class="fas fa-minus-circle text-white"></i> </button>';

            array_push($dt, array($supplier->id, $supplier->supplier_name, $supplier->last_name, $supplier->supplier_company, $supplier->email, $supplier->phone_1, $act));
    }
        $output = array(
            "draw" => intval($request->draw),
            "recordsTotal" => Supplier::get()->count(),
            "recordsFiltered" => intval(Supplier::where(function($q) use ($keyword) {
                $q->where("supplier_name", "LIKE", "%" . $keyword. "%")->orWhere("last_name", "LIKE", "%" . $keyword. "%")->orWhere("supplier_company", "LIKE", "%" . $keyword. "%")->orWhere("email", "LIKE", "%" . $keyword. "%")->orWhere("phone_1", "LIKE", "%" . $keyword. "%");
            })->orderBy($orders[0], $orders[1])->get()->count()),
            "data" => $dt
        );

        echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $supplier = Supplier::whereId($request->id)->first();
            if(!$supplier){
                abort(404, "Data not found");
            }


            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "supplier" => $supplier
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
