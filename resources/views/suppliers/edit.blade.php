<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold leading-tight text-gray-800">Ubah Supplier</h1></x-slot>
    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <x-card>@include('suppliers.form', ['supplier' => $supplier])</x-card>
        </div>
    </div>
</x-app-layout>
