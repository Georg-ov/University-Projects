<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\Address;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\SubscriptionService;


class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function showSubscribeForm()
    {
        return view('auth.subscribe');
    }

    public function processSubscription(Request $request)
    {
        // Convertir el valor del mes a entero
        $request->merge([
            'card_number' => preg_replace('/\s+/', '', $request->card_number),
            'expiration_month' => (int) $request->expiration_month,
        ]);

        // Validación de la entrada
        $request->validate([
            'card_number' => 'required|numeric',
            'cardholder_name' => 'required|string|max:255',
            'expiration_month' => 'required|integer|between:1,12',
            'expiration_year' => 'required|integer|min:' . date('Y'),
            'cvv' => 'required|numeric|digits:3',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|numeric|max:999999',
            'country' => 'required|string|max:255',
        ]);

        // Procesar la suscripción
        $success = $this->subscriptionService->processSubscription($request->all());

        if ($success) {
            Log::info('Subscription successful!');
            return redirect()->route('mainpage')->with('success', 'Subscription successful!');
        } else {
            Log::info('Subscription failed. Please try again.');
            return redirect()->back()->withErrors(['message' => 'Subscription failed. Please try again.']);
        }
    }
}
