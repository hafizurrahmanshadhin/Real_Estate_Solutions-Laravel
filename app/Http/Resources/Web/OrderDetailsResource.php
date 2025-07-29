<?php

namespace App\Http\Resources\Web;

use App\Models\AddOn;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $property    = $this->properties->first();
        $appointment = $this->appointments->first();

        // Format date and time
        $formattedDate = $appointment ? Carbon::parse($appointment->date)->format('M d, Y') : '';
        $formattedTime = $appointment ? date('g:i A', strtotime($appointment->time)) : '';

        $items = $this->items->map(function ($item) {
            $serviceName = 'Unknown';
            if ($item->itemable_type === Service::class) {
                $serviceName = method_exists($item->itemable, 'serviceItems')
                ? $item->itemable->serviceItems->pluck('service_name')->join(', ')
                : 'Service';
            } elseif ($item->itemable_type === AddOn::class) {
                $serviceName = method_exists($item->itemable, 'serviceItem') && $item->itemable->serviceItem
                ? $item->itemable->serviceItem->service_name
                : 'Add-On';
                if (method_exists($item->itemable, 'isCommunityImages') && $item->itemable->isCommunityImages()) {
                    $serviceName .= ' (' . $item->itemable->locations . ' locations)';
                }
            }
            return [
                'name'       => $serviceName,
                'quantity'   => $item->quantity,
                'unit_price' => number_format($item->unit_price, 2),
                'line_total' => number_format($item->line_total, 2),
            ];
        });

        $totalOrderPrice = $this->items->sum(function ($item) {
            return $item->line_total;
        });

        return [
            'id'               => $this->id,
            'transaction_id'   => $this->transaction_id,
            'full_name'        => $this->full_name,
            'email'            => $this->email,
            'phone_number'     => $this->phone_number,
            'address'          => $property ? $property->full_address : '',
            'property_type'    => $property ? $property->property_type : '',
            'footage_size'     => $property && $property->footageSize ? $property->footageSize->size : '',
            'appointment_date' => $formattedDate,
            'appointment_time' => $formattedTime,
            'order_status'     => $this->order_status,
            'total_amount'     => number_format($this->total_amount, 2),
            'currency'         => strtoupper($this->currency),
            'items'            => $items,
            'items_total'      => number_format($totalOrderPrice, 2),
        ];
    }
}
