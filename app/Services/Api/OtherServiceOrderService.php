<?php

namespace App\Services\Api;

use App\Models\OtherServiceOrder;

class OtherServiceOrderService {
    /**
     * Place an order for other services.
     *
     * @param array $data
     * @return OtherServiceOrder
     */
    public function placeOrder(array $data): OtherServiceOrder {
        return OtherServiceOrder::create($data);
    }
}
