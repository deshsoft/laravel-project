<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Asset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4 text-lg font-bold">Add New Asset</h2>

                    <form method="POST" action="{{ route('assets.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Asset Type</label>
                            <input type="text" name="asset_type" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Mode</label>
                            <select name="mode" id="mode" class="form-control">
                                <option value="">Select Model</option>
                                <option value="aggregable">Aggregable</option>
                                <option value="non-aggregable">Non-Aggregable</option>
                            </select>
                        </div>

                        <div class="mb-4" id="asset_size_container" style="display: none;">
                            <label class="block font-medium text-sm">Asset Size (Room)</label>
                            <input type="text" name="asset_size" id="asset_size" class="form-control">
                        </div>

                        <div class="mb-4" id="asset_qty_container" style="display: none;">
                            <label class="block font-medium text-sm">Available Quantity</label>
                            <input type="number" name="available_quantity" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm">Rental Value</label>
                            <input type="number" step="0.01" name="rental_value" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm">Fixed/Hourly</label>
                            <select name="fixed_hourly" id="fixed_hourly" class="form-control">
                                <option value="fixed">Fixed</option>
                                <option value="hourly">Hourly</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Save Asset</button>
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