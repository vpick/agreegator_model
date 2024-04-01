<?php
namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class UserExport implements FromCollection, WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
	protected $username;
    protected $user_code;
    protected $email;
	protected $mobile;
	protected $company_id;
	protected $client_id;
    protected $warehouse_id;
	protected $client_map;
    protected $warehouse_map;
	protected $multi_location;
	protected $multi_client;
	protected $role_id;
	protected $user_type;
	protected $status;
	
	public function __construct($user_code,$user_type,$status)
    {
        //$this->username = $username;
		$this->user_code = $user_code;
// 		$this->email = $email;
// 		$this->mobile = $mobile; 
// 		$this->company_id = $company_id;
//      $this->client_id = $client_id;
//      $this->warehouse_id = $warehouse_id;
//      $this->client_map = $client_map;
//      $this->warehouse_map = $warehouse_map;
//      $this->multi_location = $multi_location;
//      $this->multi_client = $multi_client;
//      $this->role_id = $role_id;
        $this->user_type = $user_type;
        $this->status = $status;
		
	
    }
    public function collection()
    {
        if(Session::has('client')){
            $clientId = session('client.id');
             $clientName = session('client.name');
        }
        else{
            $clientId = Auth::user()->client->id;
            $clientName = Auth::user()->client->name;
        }
        if(Session::has('warehouse')){
            $warehouseId = session('warehouse.id');
             $warehouseName = session('warehouse.warehouse_name');
        }
        else{
            $warehouseId = Auth::user()->warehouse->id;
            $warehouseName = Auth::user()->warehouse->warehouse_name;
        }
        $user = User::with('company','client','warehouse','role')
                    ->where('user_type','!=','isSystem')
                    ->where('user_type','!=','isCompany')
                    ->where('client_id',$clientId)
                    ->where('warehouse_id',$warehouseId)
                    ->where('id','!=',Auth::user()->id);
        
		if ($this->user_code) {
			$user = $user->where('user_code', $this->user_code);
		}
		if ($this->user_type) {
			$user = $user->where('user_type', $this->user_type);
		}
		if ($this->status) {
			$user =$user->where('status', $this->status);
		}
			$user = $user->get();
			
		return $user;
	}
	public function headings(): array
    {
        return ['username','user_code','email','mobile','company_id','client_id','warehouse_id','client_map','warehouse_map','multi_location','multi_client','role_id','user_type','status']; // Empty array to exclude headers
    }
    public function map($row): array
    {
        #dd($row);
        // Map data to desired format
        return 
        [
            $row->username,
    		$row->user_code,
    		$row->email,
    		$row->mobile, 
    		$row->company_id,
            $row->client_id,
            $row->warehouse_id,
            $row->client_map,
            $row->warehouse_map,
            $row->multi_location,
            $row->multi_client,
            $row->role_id,
            $row->user_type,
            $row->status,
        ];
    }
}