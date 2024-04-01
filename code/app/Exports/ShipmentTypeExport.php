<?php
namespace App\Exports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ShipmentType;
class ShipmentTypeExport implements FromCollection, WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $shipmentType;
   
    
	public function __construct($shipmentType)
    {
        $this->shipmentType = $shipmentType;

    }
    public function collection()
    {
        $shipmentTypeQuery = ShipmentType::orderByDesc('id');

		if ($this->shipmentType) {
			$shipmentTypeQuery->whereIn('shipment_type', explode(',', $this->shipmentType));
		}

		
		
		return $shipmentTypeQuery->get();
	}
	public function headings(): array
    {
        return ['shipment_type']; // Empty array to exclude headers
    }
    public function map($row): array
    {
        #dd($row);
        // Map data to desired format
        return 
        [
            $row->shipment_type,
            
        ];
    }
}