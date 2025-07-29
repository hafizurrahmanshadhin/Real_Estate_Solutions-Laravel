<?php

namespace App\Services\Api;

use App\Mail\OtherServiceOrderAdminMail;
use App\Mail\OtherServiceOrderUserMail;
use App\Models\OtherServiceOrder;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtherServiceOrderService {
    /**
     * Place an order for other services.
     *
     * @param array $data
     * @return OtherServiceOrder
     */
    public function placeOrder(array $data): OtherServiceOrder {
        $order = OtherServiceOrder::create($data);

        // Send confirmation to user
        try {
            Mail::to($order->email)->send(new OtherServiceOrderUserMail($order));
        } catch (Exception $e) {
            // Optionally log or handle user mail failure
            Log::error('User order email failed: ' . $e->getMessage());
        }

        // Get platform owner email from SystemSetting
        $adminEmail = SystemSetting::query()->value('email');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new OtherServiceOrderAdminMail($order));
            } catch (Exception $e) {
                // Optionally log or handle admin mail failure
                Log::error('Admin order email failed: ' . $e->getMessage());
            }
        }

        return $order;
    }
}
