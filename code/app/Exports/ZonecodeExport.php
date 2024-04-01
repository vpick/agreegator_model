<?php
namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Zone;
class ZonecodeExport implements FromCollection, WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
	protected $zonecode;
    protected $pincode;
    protected $courier;
	protected $areacode;
	protected $hubname;
	protected $city;
    protected $state;
	protected $delivery;
    protected $odestates;
	protected $codedelivery;
	protected $prepaiddelivery;
	protected $pickup;
	protected $reversepickup;
	
	public function __construct($zonecode,$pincode,$courier,$hubname,$city,$state)
    {
        $this->zonecode = $zonecode;
		$this->pincode = $pincode;
		$this->courier = $courier;
		
		$this->hub_name = $hubname;
        $this->city = $city;
        $this->state = $state;
		
		/*
		$this->area_code = $areacode;
		$this->delivery = $delivery;
		$this->oda_states = $odestates;
		$this->cod_delivery = $codedelivery;
		$this->prepaid_delivery = $prepaiddelivery;
		$this->pickup = $pickup;
		$this->reverse_pickup = $reversepickup;
		*/
    }
    public function collection()
    {
        $zonecodeQuery = Zone::orderByDesc('id');
        if ($this->zonecode) {
			$zonecodeQuery->whereIn('zone_code', explode(',', $this->zonecode));
		} 
		if ($this->pincode) {
			$zonecodeQuery->whereIn('pin_code', explode(',', $this->pincode));
		}

		if ($this->hubname) {
			$zonecodeQuery->whereIn('hub_name', explode(',', $this->hubname));
		}
		if ($this->courier) {
			$zonecodeQuery->whereIn('courier', explode(',', $this->courier));
		}
        if ($this->city) {
			$zonecodeQuery->whereIn('city', explode(',', $this->city));
		}

		if ($this->state) {
			$zonecodeQuery->whereIn('state', explode(',', $this->state));
		}
		
		return $zonecodeQuery->get();
	}
	public function headings(): array
    {
        return ['Zone_Code','Pin_Code','Courier','Area_Code','Hub_Name','City','State','Delivery','ODA_states','COD_Delivery','Prepaid_Delivery','Pickup','Reverse_Pickup']; // Empty array to exclude headers
    }
    public function map($row): array
    {
        #dd($row);
        // Map data to desired format
        return 
        [
            $row->zone_code,
			$row->pin_code,
            $row->courier,
			$row->area_code,
			$row->hub_name,
            $row->city,
            $row->state,
			$row->delivery,
			$row->oda_states,
			$row->cod_delivery,
			$row->prepaid_delivery,
			$row->pickup,
			$row->reverse_pickup,
        ];
    }
}