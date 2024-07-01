@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.hook('message.processed', (message, component) => {
                if (component.fingerprint.name === 'create-vehicle-modal' || component.fingerprint.name === 'delete-modal') {
                    if (!component.get('show')) {
                        document.body.classList.remove('overflow-hidden');
                    }
                }
            });
        });
    </script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-2">
                @lang('vehicle.title')
            </h2>
            <livewire:create-vehicle-modal />
        </div>

    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sm:p-6 lg:p-8">
                <livewire:vehicle-table/>
                <livewire:delete-modal />
            </div>
        </div>
    </div>
</x-app-layout>
