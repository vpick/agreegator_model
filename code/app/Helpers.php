<?php

namespace App\Helpers;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Client;
use App\Models\User;
use App\Models\Warehouse;
class Helper {

    //numseriers generate
    function numSeries($doc){
        if($doc == 'company'){
            $data = Company::orderBy('id','desc')->first();
            if(!empty($data)){            
                $companyCode =$data->company_code;
                $str = explode('_',$companyCode);
                $num = $str[1]+1;
                $numSeries = Str::of($str[0])->append('_'.$num);
                return $numSeries;
            }
            else{
                $prefix ='com_';
                $start = '100';
                $numSeries = $prefix.$start;
                return $numSeries;
            }
        }
        else if($doc == 'client'){
            $data = Client::orderBy('id','desc')->first();
            if(!empty($data)){            
                $clientCode =$data->client_code;
                $str = explode('_',$clientCode);
                $num = $str[1]+1;
                $numSeries = Str::of($str[0])->append('_'.$num);
                return $numSeries;
            }
            else{
                $prefix ='cl_';
                $start = '100';
                $numSeries = $prefix.$start;
                return $numSeries;
            }
        }
        else if($doc == 'warehouse'){
            $data = Warehouse::orderBy('id','desc')->first();
            if(!empty($data)){            
                $warehouseCode =$data->warehouse_code;
                $str = explode('_',$warehouseCode);
                $num = $str[1]+1;
                $numSeries = Str::of($str[0])->append('_'.$num);
                return $numSeries;
            }
            else{
                $prefix ='wrh_';
                $start = '100';
                $numSeries = $prefix.$start;
                return $numSeries;
            }
        }
        else if($doc == 'user'){
            $data = User::orderBy('id','desc')->first();
            
            if(!empty($data)){            
                $userCode =$data->user_code;
                $str = explode('_',$userCode);
                $num = $str[1]+1;
                $numSeries = Str::of($str[0])->append('_'.$num);
                return $numSeries;
            }
            else{
                $prefix ='user_';
                $start = '100';
                $numSeries = $prefix.$start;
                return $numSeries;
            }
        }
        
    }
}
?>