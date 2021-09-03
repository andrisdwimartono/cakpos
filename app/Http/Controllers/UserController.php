<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\User_menu;
use App\Models\Menu;
use Hash;

class UserController extends Controller
{
    public function tabledesign(){
        $td = [
            "page_data_name" => "User",
            "page_data_urlname" => "user",
            "fields" => [
                "name" => "text",
                "email" => "text",
                "password" => "text",
                "foto" => "upload"
            ],
            "fieldschildtable" => [
            ]
        ];

        $td["fieldsrules"] = [
            "name" => "required|min:4|max:255",
            "email" => "required|min:4|max:255",
            "password" => "required|min:5|max:100"
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
        
        return view("user.list", ["page_data" => $page_data]);
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
        $page_data["footer_js_page_specific_script"] = ["user.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        return view("user.create", ["page_data" => $page_data]);
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
            $id = User::create([
                "name"=> $request->name,
                "email"=> $request->email,
                "password"=> Hash::make($request->password),
                "foto"=> $request->foto,
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
    public function show(User $user)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "View";
        $page_data["footer_js_page_specific_script"] = ["user.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $user->id;
        return view("user.create", ["page_data" => $page_data]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["user.page_specific_script.footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $user->id;
        return view("user.create", ["page_data" => $page_data]);
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
            User::where("id", $id)->update([
                "name"=> $request->name,
                "email"=> $request->email,
                "password"=> Hash::make($request->password),
                "foto"=> $request->foto,
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
            $user = User::whereId($request->id)->first();
            if(!$user){
                abort(404, "Data not found");
            }
            $results = array(
                "status" => 417,
                "message" => "Deleting failed"
            );
            if(User::whereId($request->id)->forceDelete()){
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
        $list_column = array("id", "name", "email");
		$keyword = null;
		if(isset($request->search["value"])){
			$keyword = $request->search["value"];
		}
		
		if(isset($code_id) && $code_id != ""){
			$keyword = $code_id;
		}
		
		$orders = array('id', 'ASC');
		if(isset($request->order)){
			$orders = array($list_column[$request->order['0']['column']], $request->order['0']['dir']);
		}
		
		$limit = null;
		if(isset($request->length) && $request->length != -1){
			$limit = array(intval($request->start), intval($request->length));
		}
		
        $dt = array();
        $no = 0;
        foreach(User::where(function($q) use ($keyword) {
            $q->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%');
        })->orderBy($orders[0], $orders[1])->offset($limit[0])->limit($limit[1])->get($list_column) as $user){
            $no = $no+1;
            $act = '
            <a href="/assignmenu/'.$user->id.'/edit" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Menus to this user"><i class="fas fa-bars text-white"></i></a>

            <a href="/user/'.$user->id.'" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail"><i class="fas fa-eye text-white"></i></a>
            
            <a href="/user/'.$user->id.'/edit" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fas fa-edit text-white"></i></a>
            
            <button type="button" class="btn btn-danger row-delete">
                <i class="fas fa-minus-circle text-white"></i>
            </button>';
            //array_push($dt, array($no+$limit[0], $user->nama, $user->email, $act));
            array_push($dt, array($user->id, $user->name, $user->email, $act));
        }
		$output = array(
			"draw"    => intval($request->draw),
			"recordsTotal"  =>  User::get()->count(),
			"recordsFiltered" => intval(User::where(function($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%');
            })->orderBy($orders[0], $orders[1])->get()->count()),
			"data"    => $dt
		);
		
		echo json_encode($output);
    }

    public function getdata(Request $request)
    {
        if($request->ajax()){
            $user = User::whereId($request->id)->first();
            if(!$user){
                abort(404, "Data not found");
            }


            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "user" => $user
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


    public function assignmenu(User $user)
    {
        $page_data = $this->tabledesign();
        $page_data["page_method_name"] = "Update";
        $page_data["footer_js_page_specific_script"] = ["user.page_specific_script.assignmenu_footer_js_create"];
        $page_data["header_js_page_specific_script"] = ["paging.page_specific_script.header_js_create"];
        
        $page_data["id"] = $user->id;
        $menus = Menu::orderBy("mp_sequence", "ASC")->orderBy("m_sequence", "ASC")->get();
        return view('user.assignmenu', ['page_data' => $page_data, 'menus' => $menus]);
    }

    public function getdataassignmenuuser(Request $request)
    {
       if($request->ajax()){
            $user = User_menu::whereUserId($request->id)->get();
            if(!$user){
                abort(404, "Data not found");
            }

            $results = array(
                "status" => 201,
                "message" => "Data available",
                "data" => [
                    "user" => $user
                ]
            );

            return response()->json($results);
        }
    }

    public function updateassignmenu(Request $request, $id)
    {
        $page_data = $this->tabledesign();
        
        foreach(Menu::whereNull("is_group_menu")->get() as $menu){
            if(User_menu::where("user_id", $id)->where("menu_id", $menu->id)->first()){
                User_menu::where("user_id", $id)->where("menu_id", $menu->id)->update([
                    "is_granted" => $request["menu_".$menu->id],
                    "mp_sequence" => $menu->mp_sequence,
                    "m_sequence" => $menu->m_sequence,
                    "menu_name" => $menu->menu_name,
                    "url" => $menu->url,
                    "menu_icon" => $menu->menu_icon,
                    "parent_id" => $menu->parent_id,
                    "is_group_menu" => $menu->is_group_menu,
                    "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                ]);
            }else{
                User_menu::create([
                    "user_id" => $id,
                    "menu_id" => $menu->id,
                    "is_granted" => $request["menu_".$menu->id],
                    "mp_sequence" => $menu->mp_sequence,
                    "m_sequence" => $menu->m_sequence,
                    "menu_name" => $menu->menu_name,
                    "url" => $menu->url,
                    "menu_icon" => $menu->menu_icon,
                    "parent_id" => $menu->parent_id,
                    "is_group_menu" => $menu->is_group_menu,
                    "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                ]);
            }
        }

        foreach(Menu::whereNotNull("is_group_menu")->get() as $menu){
            if(User_menu::where("user_id", $id)->where("is_granted", 'on')->where("parent_id", $menu->id)->first()){
                if(User_menu::where("user_id", $id)->where("menu_id", $menu->id)->first()){
                    User_menu::where("user_id", $id)->where("menu_id", $menu->id)->update([
                        "is_granted" => 'on',
                        "mp_sequence" => $menu->mp_sequence,
                        "m_sequence" => $menu->m_sequence,
                        "menu_name" => $menu->menu_name,
                        "url" => $menu->url,
                        "menu_icon" => $menu->menu_icon,
                        "parent_id" => null,
                        "is_group_menu" => 'on',
                        "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                    ]);
                }else{
                    User_menu::create([
                        "user_id" => $id,
                        "menu_id" => $menu->id,
                        "is_granted" => 'on',
                        "mp_sequence" => $menu->mp_sequence,
                        "m_sequence" => $menu->m_sequence,
                        "menu_name" => $menu->menu_name,
                        "url" => $menu->url,
                        "menu_icon" => $menu->menu_icon,
                        "parent_id" => null,
                        "is_group_menu" => 'on',
                        "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                    ]);
                }
            }else{
                if(User_menu::where("user_id", $id)->where("menu_id", $menu->id)->first()){
                    User_menu::where("user_id", $id)->where("menu_id", $menu->id)->update([
                        "is_granted" => null,
                        "mp_sequence" => $menu->mp_sequence,
                        "m_sequence" => $menu->m_sequence,
                        "menu_name" => $menu->menu_name,
                        "url" => $menu->url,
                        "menu_icon" => $menu->menu_icon,
                        "parent_id" => null,
                        "is_group_menu" => 'on',
                        "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                    ]);
                }else{
                    User_menu::create([
                        "user_id" => $id,
                        "menu_id" => $menu->id,
                        "is_granted" => null,
                        "mp_sequence" => $menu->mp_sequence,
                        "m_sequence" => $menu->m_sequence,
                        "menu_name" => $menu->menu_name,
                        "url" => $menu->url,
                        "menu_icon" => $menu->menu_icon,
                        "parent_id" => null,
                        "is_group_menu" => 'on',
                        "is_shown_at_side_menu" => $menu->is_shown_at_side_menu
                    ]);
                }
            }
        }
            
        return response()->json([
            'status' => 201,
            'message' => 'ID '.$id.' successfully updated',
            'data' => ['id' => $id]
        ]);
        
    }
}
