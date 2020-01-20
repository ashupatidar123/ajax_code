<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CoreController;
use App\Models\Admin\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
//use Illuminate\Support\Facades\DB;

class AdminController extends Controller{

	protected $adminModel;
	public function __construct(){
		parent::__construct();
		$this->adminModel = new AdminModel();     	

  	}

  	public function print($data){		
		echo "<pre>";
		print_r($data);
		echo "<pre>";
		dd();
	}
	public function imageUpload($fileName,$folder='',$url='')
	{		
		$name = time().'.'.$fileName->getClientOriginalExtension();
        $destinationPath = public_path($folder);
        $fileName->move($destinationPath,$name);
        return $name;
	}

	public function imageUploadMulti($fileName,$folder='',$url='')
	{		
        $hole_images=array();

	    foreach($fileName as $file)
	    {
            $name = time().'_'.$file->getClientOriginalName();
            //$name = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path($folder);
            $file->move($destinationPath,$name);
            $hole_images[]=$name;	        
	    }
	    return $hole_images;
	}

	public function headerDetails(){
		return $total_user = $this->adminModel->countQuery('users');
	}

	public function dashboard(Request $req){		
		return view('admin.dashboard');
	}

	public function register(Request $req){		
		return view('admin.form');
	}

	public function insert(Request $req){
		
		if(!empty($req->file('image'))){
			$image = AdminController::imageUpload($req->file('image'),'/uploads/user/');
		}else{
			$image = '';
		}

		if(!empty($req->file('image_multi'))){
			$image_multi = AdminController::imageUploadMulti($req->file('image_multi'),'/uploads/user/');
			$image_multi = implode(",", $image_multi);
		}else{
			$image_multi = '';
		}
   		//echo $image_multi; die();
		$username = $req->input('username');
		$where = array('username'=>$username);
		//$userData = $this->adminModel->fetchQuerySingle('users','username',$where,$orderName='user_id',$ascDESC='DESC',$or_where=NULL,0,1);
		$userData = $this->adminModel->countQuery('users',$where);
		if($userData>0){
			$msg = array('alert'=>'warning','message'=>$username.' username is already exist.');
			return redirect('webpanel/register')->with($msg);
		}
		
		$data = array(
			'Email'=>$req->input('Email'),
			'Mobile'=>$req->input('Mobile'),
			'username'=>$req->input('username'),
			'Name'=>$req->input('Name'),
			'Password'=>$req->input('Password'),
			'user_image'=>$image,
			'multi_image'=>$image_multi

		);		
		if(($this->adminModel->insertQuery('users',$data))>0){
			$msg = array('alert'=>'success','message'=>'User registration success!');
			return redirect('webpanel/register')->with($msg);
		}else{
			$msg = array('alert'=>'danger','message'=>'Registration failed!');
			return redirect('webpanel/register')->with($msg);
		}
	}

	// $where = [
		// 	['username','Ram'],
		// 	['password','1111']
		// ];

		// $or_where = [
		// 	['mobile','9922554151'],
		// 	['password','1111']
		// ];
		//$or_where = array('username'=>'Dp','Name'=>'sy');
		//$userData['record'] = $this->adminModel->fetchQuery('users','*',$where,$or_where='',$orderName='user_id',$ascDESC='DESC',$groupBy='',$start='0',$end=500000);

	public function displayPagination($per_page,$page,$total,$page_url)
	{
	    $page_url = url($page_url); //url laravel base url
    	    	
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $setLastpage = ceil($total/$per_page);
    	$lpm1 = $setLastpage - 1;
    	
    	$setPaginate = "";
    	if($setLastpage > 1)
    	{	
    		$setPaginate .= "<ul class='setPaginate'>";
                    $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
    		if ($setLastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $setLastpage; $counter++)
    			{
    				if ($counter == $page)
    					$setPaginate.= "<li><a class='current_page'>$counter</a></li>";
    				else
    					$setPaginate.= "<li><a href='{$page_url}/$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($setLastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<li><a class='current_page'>$counter</a></li>";
    					else
    						$setPaginate.= "<li><a href='{$page_url}/$counter'>$counter</a></li>";					
    				}
    				$setPaginate.= "<li class='dot'>...</li>";
    				$setPaginate.= "<li><a href='{$page_url}/$lpm1'>$lpm1</a></li>";
    				$setPaginate.= "<li><a href='{$page_url}/$setLastpage'>$setLastpage</a></li>";		
    			}
    			elseif($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$setPaginate.= "<li><a href='{$page_url}/1'>1</a></li>";
    				$setPaginate.= "<li><a href='{$page_url}/2'>2</a></li>";
    				$setPaginate.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<li><a class='current_page'>$counter</a></li>";
    					else
    						$setPaginate.= "<li><a href='{$page_url}/$counter'>$counter</a></li>";					
    				}
    				$setPaginate.= "<li class='dot'>..</li>";
    				$setPaginate.= "<li><a href='{$page_url}/$lpm1'>$lpm1</a></li>";
    				$setPaginate.= "<li><a href='{$page_url}/$setLastpage'>$setLastpage</a></li>";		
    			}
    			else
    			{
    				$setPaginate.= "<li><a href='{$page_url}/1'>1</a></li>";
    				$setPaginate.= "<li><a href='{$page_url}/2'>2</a></li>";
    				$setPaginate.= "<li class='dot'>..</li>";
    				for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<li><a class='current_page'>$counter</a></li>";
    					else
    						$setPaginate.= "<li><a href='{$page_url}/$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$setPaginate.= "<li><a href='{$page_url}/$next'>Next</a></li>";
                $setPaginate.= "<li><a href='{$page_url}/$setLastpage'>Last</a></li>";
    		}else{
    			$setPaginate.= "<li><a class='current_page'>Next</a></li>";
                $setPaginate.= "<li><a class='current_page'>Last</a></li>";
            }

    		$setPaginate.= "</ul>\n";		
    	}
        return $setPaginate;
    } 

    public function userSearch($search)
	{
		$where='';
		if(!empty($userId=$search['userId'])){
			$where .= "AND user_id='$userId' ";
		}
		if(!empty($username=$search['username'])){
			$where .= "AND username LIKE '$username%' ";
		}
		if(!empty($name=$search['name'])){
			$where .= "AND Name LIKE '$name%' ";
		}
		if(!empty($mobile=$search['mobile'])){
			$where .= "AND Mobile='$mobile' ";
		}
		if(!empty($email=$search['email'])){
			$where .= "AND Email LIKE '$email%' ";
		}
		if(!empty($from_date=$search['from_date'])){
			$where .= "AND date(created_date)>='$from_date' ";
		}
		if(!empty($to_date=$search['to_date'])){
			$where .= "AND date(created_date)<='$to_date' ";
		}
		return $where;
	}

	public function userList($page='1',Request $req)
	{
		$search_page=0;
		if(isset($_POST['session_des'])){
			Session::forget('userSearch');
			return redirect('webpanel/userList');
		}
		$where = "WHERE user_status=1 ";
		if(!empty($search = $req->input())){	
			Session::put('userSearch',$search);
			$where .= $this->userSearch($search);

			if(!empty($search['per_page'])){
				$search_page = $search['per_page'];
			}
		}
		else if(!empty(Session::get('userSearch'))){	
			$where .= $this->userSearch(Session::get('userSearch'));
			$pg = Session::get('userSearch');
			$search_page = $pg['per_page'];
		}
		
		$per_page = ($search_page>0)?$search_page:15;
		$start = ($page * $per_page) - $per_page;
		
		$total 	  = $this->adminModel->customCountQuery('users',$where);
		$userData = $this->adminModel->customFetchQuery('*',$tbl='users',$where,'ORDER BY user_id DESC',$limit="LIMIT $start,$per_page");

		$userData['record'] = $userData;
		$userData['pagination'] = $this->displayPagination($per_page,$page,$total,'webpanel/userList');
		//AdminController::print($userData);
		$userData['sno'] = ($page==1)?1:$page*$per_page-($per_page-1);
		$userData['total'] = $total;
		return view('admin.userList',$userData);
	}

	public function userExcel(Request $req)
	{
		$results = $this->adminModel->customFetchQuery('*',$tbl='users',$where='','ORDER BY user_id DESC');
		$results = json_decode(json_encode($results), True);
		//echo "<pre>"; print_r($results); die();
		$filename = "user_details.xls";
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=$filename");  
		header("Pragma: no-cache"); 
		header("Expires: 0");

		$data =  '';
		$data .= '<table border="3">';
		$data .= '<tr style="background-color:black">';
		$data .= '<th>Sno</th><th>User ID</th><th>Username</th><th>Name</th>';
		$data .= '<th>Mobile</th><th>Email</th><th>Register Date</th>';
		$data .= '</tr>';
		$sno=1;
		foreach($results as $key => $row)
		{
			$data .= '<tr>';
			$data .= '<td>'.$sno++.'</td>';
			$data .= '<td>'.$row['user_id'].'</td>';
			$data .= '<td>'.$row['username'].'</td>';
			$data .= '<td>'.$row['Name'].'</td>';
			$data .= '<td>'.$row['Mobile'].'</td>';
			$data .= '<td>'.$row['Email'].'</td>';
			$data .= '<td>'.date('d-M-Y H:i:s',strtotime($row['created_date'])).'</td>';
			$data .= '</tr>';
		}
		$data .= '</table>';
		print_r($data);
	}

	public function userCheck(Request $req)
	{
		$username = $req->input('username');
		$where = array('username'=>$username);
		$userData = $this->adminModel->countQuery('users',$where);
		if($userData>0){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function editUser($userId,$page_name='', Request $req){		
		
		if(($user_id = CoreController::id_original($userId))<=0){
			return redirect('webpanel/userList');
		}
		$where = [
			['user_id',$user_id],
		];

		$userData['record'] = current($this->adminModel->fetchQuery('users','*',$where));
		$mul = $userData['record']->multi_image;
		$userData['multi_image'] = explode(',', $mul);
		$userData['multi_image_count'] = count(explode(',', $mul));
		$userData['userId'] = $userId;
		return view('admin.editUser',$userData);
		
	}

	public function updateUser(Request $req)
	{
		if($req->method('post')!='POST'){
 			return redirect('webpanel/userList');
		}
		
		$userId = $req->input('userId'); 
		if(($user_id = CoreController::id_original($userId))<=0){
			return redirect('webpanel/userList');
		}
		
		$data = array(
            'Mobile'=>$req->input('Mobile'),
            'Name'=>$req->input('Name')
        ); 

		if(!empty($req->file('image'))){
            $data['user_image'] = AdminController::imageUpload($req->file('image'),'/uploads/user/');
        }
        if(!empty($req->file('image_multi'))){
            $image_multis = AdminController::imageUploadMulti($req->file('image_multi'),'/uploads/user/');
            $data['multi_image'] = implode(",", $image_multis);
        }

        //$this->print($data);
        $where = [
			['user_id',$user_id]
		]; 
        if(($this->adminModel->updateQuery('users',$data,$where))>0){
            $msg = array('alert'=>'success','message'=>'User details updated successfully!');
            return redirect()->back()->with($msg);
            //return redirect('webpanel/userList')->with($msg);
        }else{
            $msg = array('alert'=>'danger','message'=>'Updation failed!');
            //return redirect('webpanel/userList')->with($msg);
            return redirect()->back()->with($msg);
        }
	}

	public function userDelete(Request $req)
	{
		$id = $req->input('id');
		if(($user_id = CoreController::id_original($id))<=0){
			return redirect('webpanel/userList');
		}
		$where = [
			['user_id',$user_id]
		];
		echo $this->adminModel->deleteQuery('users',$where);
	}

	public function task()
	{
		return view('admin.taskPage');
	}

	public function taskAdd(Request $req)
	{
		$data = $req->input();						
		$insert = array(
			'user_id'=>$data['user_id'],
			'task_name'=>$data['task_name'],
			'title'=>$data['title']
		);
		
		if(!empty($req->file('task_image'))){
            $insert['task_image'] = $this->imageUpload($req->file('task_image'),'/uploads/user/');
        }
        //printData($insert);
		if(($this->adminModel->insertQuery('task',$insert))>0){
			echo 1;
		}else{
			echo 0;
		}
	}











} //AdminController closed