<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function createAddress($data)
    {
        return DB::transaction(function () use ($data) {
            $address = new Address();
            $address->street = $data['street'];
            $address->city = $data['city'];
            $address->province = $data['province'];
            $address->postal_code = $data['postal_code'];
            $address->country = $data['country'];
            $address->user_id = $data['user_id'];
            $address->save();

            return $address;
        });
    }

    public function updateAddress($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $address = Address::findOrFail($id);
            $address->update($data);

            return $address;
        });
    }
}