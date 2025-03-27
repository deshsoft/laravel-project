<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%")
                    ->orWhere('company_address', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('customers.index', compact('customers'));
    }


    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:15',
        ]);


        if ($validator->fails()) {
            //dd($validator->errors()); // Debugging: Check if errors exist
            return redirect()->back()->withErrors($validator)->withInput();
            //return redirect()->route('customers.index')->with('success', $validator->errors());
        }

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }


    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer)
    {
        // Extract only the expected input fields
        $data = $request->only([
            'first_name',
            'last_name',
            'company_name',
            'company_address',
            'email',
            'phone'
        ]);

        // Ensure correct handling of data
        if (!isset($data['first_name']) || !isset($data['email'])) {
            return back()->withErrors(['error' => 'Invalid request data']);
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');

    }



    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}

