<?php

namespace App\Services;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function createUser($data)
    {
        return DB::transaction(function () use ($data) {
            $user = new User();
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->age = $data['birthday'];
            $user->role_type = $data['role_type'];
            $user->subscription_type = $data['subscription_type'];
            $user->subscription_expiration_date = '9999-12-31';
            $user->image_profile = $data['image_profile'] ?? 'app_images/default_profile_picture.png';
            $user->save();

            return $user;
        });
    }

    public function updateUser($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->about_me = $data['about_me'] ?? $user->about_me;

            if (isset($data['image_profile'])) {
                $user->image_profile = $data['image_profile'];
            }

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            if (isset($data['role_type'])) {
                $user->role_type = $data['role_type'];
            }

            if (isset($data['subscription_type'])) {
                $user->subscription_type = $data['subscription_type'];
            }

            $user->save();

            if ($user->subscription_type === 'PREMIUM' && isset($data['address'])) {
                $this->updateUserAddress($user, $data['address']);
            }

            return $user;
        });
    }

    private function updateUserAddress(User $user, $addressData)
    {
        $address = $user->address ?: new Address;
        $address->fill($addressData);
        $address->user()->associate($user);
        $address->save();
    }
}