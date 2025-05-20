<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Services\AddressService;

class AddressController extends Controller
{

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function listAll()
    {
        $addresses = Address::paginate(5);
        return view('admin/list_addresses', ['addresses' => $addresses]);
    }

    public function orderAddresses(Request $request)
    {
        $sort = $request->input('sort', 'id');

        // Corregir el typo en 'Adress' a 'Address'
        $addresses = Address::orderBy($sort)->paginate(5);

        return view('admin/list_addresses', compact('addresses'));
    }

    public function deleteAddress(Request $request)
{
    $id = $request->input('id'); // Recibe el ID desde el formulario

    $address = Address::find($id);

    if ($address) {
        $address->delete();
        return redirect()->route('list.addresses')->with('success', 'The address has been deleted successfully.');
    } else {
        return redirect()->route('list.addresses')->with('error', 'Address not found.');
    }
}

    

    public function createAddress($id)
    {
        $user = User::findOrFail($id);
        return view('admin/create_addresses', ['user' => $user]);
    }

    public function saveAddress(Request $request)
    {
        $validatedData = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|numeric|max:999999',
            'country' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $address = $this->addressService->createAddress($validatedData);

        return redirect('/admin/users')->with('success', 'The address has been successfully created!');
    }


    public function editAddress($id)
    {
        $address = Address::findOrFail($id);
        return view('admin/update_addresses', ['address' => $address]);
    }

    public function updateAddress(Request $request, $id)
    {
        $validatedData = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|numeric|max:999999',
            'country' => 'required|string|max:255',
        ]);

        $address = $this->addressService->updateAddress($id, $validatedData);

        return redirect()->route('list.addresses')->with('success', 'The address has been successfully updated!');
    }

    public function searchAddresses(Request $request)
    {
        // Recoger el término de búsqueda del formulario
        $search = $request->input('search');

        // Realizar la consulta en la base de datos buscando coincidencias en los campos relevantes
        $addresses = Address::where('street', 'LIKE', "%{$search}%")
                             ->orWhere('city', 'LIKE', "%{$search}%")
                             ->orWhere('province', 'LIKE', "%{$search}%")
                             ->orWhere('postal_code', 'LIKE', "%{$search}%")
                             ->orWhere('country', 'LIKE', "%{$search}%")
                             ->paginate(5);  // Paginar los resultados

        // Devolver la vista con los resultados de la búsqueda
        return view('admin/list_addresses', ['addresses' => $addresses]);
    }
}

