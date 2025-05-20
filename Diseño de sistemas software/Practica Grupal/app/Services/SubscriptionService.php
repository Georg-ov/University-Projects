<?php

namespace App\Services;

use App\Models\CreditCard;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionService
{
    public function processSubscription($data)
    {
        DB::beginTransaction();
        
        try {
            // Almacenamiento de la tarjeta de crédito en la base de datos
            CreditCard::create([
                'user_id' => Auth::id(),
                'card_number' => encrypt($data['card_number']),
                'cardholder_name' => $data['cardholder_name'],
                'expiration_month' => $data['expiration_month'],
                'expiration_year' => $data['expiration_year'],
                'cvv' => encrypt($data['cvv']),
            ]);

            // Almacenamiento de la dirección en la base de datos
            Address::create([
                'user_id' => Auth::id(),
                'street' => $data['street'],
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'country' => $data['country'],
            ]);

            // Lógica ficticia de suscripción
            $user = Auth::user();
            $user->subscription_type = 'PREMIUM'; 
            $user->subscription_expiration_date = Carbon::now()->addMonth();
            $user->save();

            DB::commit();
            
            Log::info('Subscription processed successfully', ['user_id' => $user->id]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Subscription processing failed', ['error' => $e->getMessage()]);
            
            return false;
        }
    }
}
