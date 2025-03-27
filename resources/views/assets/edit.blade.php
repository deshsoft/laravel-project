<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Asset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4 text-lg font-bold">Edit Asset</h2>

                    <form method="POST" action="{{ route('assets.update', $asset) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Asset Type</label>
                            <input type="text" name="asset_type" class="form-control" value="{{ $asset->asset_type }}"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Mode</label>
                            <select name="mode" id="mode" class="form-control">
                                <option value="aggregable" {{ $asset->mode == 'aggregable' ? 'selected' : '' }}>Aggregable
                                </option>
                                <option value="non-aggregable" {{ $asset->mode == 'non-aggregable' ? 'selected' : '' }}>
                                    Non-Aggregable</option>
                            </select>
                        </div>

                        <div class="mb-4" id="asset_size_container"
                            style="{{ $asset->mode == 'aggregable' ? 'display: block;' : 'display: none;' }}">
                            <label class="block font-medium text-sm">Asset Size (Room)</label>
                            <input type="text" name="asset_size" id="asset_size" class="form-control"
                                value="{{ $asset->asset_size }}">
                        </div>

                        <div class="mb-4" id="asset_qty_container"
                            style="{{ $asset->mode == 'non-aggregable' ? 'display: block;' : 'display: none;' }}">
                            <label class="block font-medium text-sm">Available Quantity</label>
                            <input type="number" name="available_quantity" class="form-control"
                                value="{{ $asset->available_quantity }}">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Rental Value</label>
                            <input type="number" step="0.01" name="rental_value" class="form-control"
                                value="{{ $asset->rental_value }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm">Fixed/Hourly</label>
                            <select name="fixed_hourly" id="fixed_hourly" class="form-control">
                                <option value="fixed" {{ $asset->fixed_hourly == 'fixed' ? 'selected' : '' }}>Fixed
                                </option>
                                <option value="hourly" {{ $asset->fixed_hourly == 'hourly' ? 'selected' : '' }}>Hourly
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning">Update Asset</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('mode').addEventListener('change', function () {
            let assetSizeContainer = document.getElementById('asset_size_container');
            assetSizeContainer.style.display = this.value === 'aggregable' ? 'block' : 'none';

            let assetQtyContainer = document.getElementById('asset_qty_container');
            assetQtyContainer.style.display = this.value === 'non-aggregable' ? 'block' : 'none';
        });
    </script>
</x-app-layout>