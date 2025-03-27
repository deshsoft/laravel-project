<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2 class="mb-4 text-lg font-bold">Customers</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('customers.index') }}" class="mb-3 flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search customers..." class="form-control w-50">
                        <button type="submit" class="btn btn-info">Search</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-primary">Reset</a>
                        <a href="{{ route('customers.create') }}" class="btn btn-success">Add Customer</a>
                    </form>


                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                    <tr>
                                    <td>{{ $customer->first_name }}</td>
                                    <td>{{ $customer->last_name }}</td>
                                    <td>{{ $customer->company_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $customers->appends(['search' => request('search')])->links() }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>