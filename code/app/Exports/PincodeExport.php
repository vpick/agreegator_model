<?php
namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Pincode;
class PincodeExport implements FromCollection, WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $pincode;
    protected $district;
    protected $city;
    protected $state;
    
	public function __construct($pincode,$district,$city,$state)
    {
        $this->pincode = $pincode;
        $this->district = $district;
        $this->city = $city;
        $this->state = $state;
    }
    public function collection()
    {
        $pincodeQuery = Pincode::orderByDesc('id');

		if ($this->pincode) {
			$pincodeQuery->whereIn('pincode', explode(',', $this->pincode));
		}

		if ($this->district) {
			$pincodeQuery->whereIn('district', explode(',', $this->district));
		}

		if ($this->city) {
			$pincodeQuery->whereIn('city', explode(',', $this->city));
		}

		if ($this->state) {
			$pincodeQuery->whereIn('state', explode(',', $this->state));
		}
		
		return $pincodeQuery->get();
	}
	public function headings(): array
    {
        return ['Pincode','District','City','State']; // Empty array to exclude headers
    }
    public function map($row): array
    {
        #dd($row);
        // Map data to desired format
        return 
        [
            $row->pincode,
            $row->district,
            $row->city,
            $row->state,
        ];
    }
}