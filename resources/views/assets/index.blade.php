<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assets List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4 text-lg font-bold">Assets</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form method="GET" action="{{ route('assets.index') }}" class="mb-3 flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..."
                            class="form-control w-50">
                        <button type="submit" class="btn btn-info">Search</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-primary">Reset</a>
                        <a href="{{ route('assets.create') }}" class="btn btn-success">Add Asset</a>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Asset Type</th>
                                <th>Mode</th>
                                <th>Asset Size</th>
                                <th>Quantity</th>
                                <th>Rental Value</th>
                                <th>Fixed/Hourly</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                                    <tr>
                                    <td>{{ $asset->asset_type }}</td>
                                    <td>{{ $asset->mode }}</td>
                                    <td>{{ $asset->mode === 'aggregable' ? $asset->asset_size : 'N/A' }}</td>
                                    <td>{{ $asset->mode === 'non-aggregable' ? $asset->available_quantity : 'N/A' }}</td>

                                    <td>€{{ number_format($asset->rental_value, 2) }}</td>
                                    <td>{{ $asset->fixed_hourly }}</td>
                                    <td>
                                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $assets->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>