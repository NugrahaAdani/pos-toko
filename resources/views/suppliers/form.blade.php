<form action="{{ $supplier ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($supplier)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama supplier</label>
        <input id="name" name="name" type="text" value="{{ old('name', $supplier?->name) }}" required maxlength="150"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="contact_person" class="block text-sm font-medium text-gray-700">Nama kontak</label>
            <input id="contact_person" name="contact_person" type="text" value="{{ old('contact_person', $supplier?->contact_person) }}" maxlength="150"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('contact_person') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $supplier?->phone) }}" maxlength="30"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $supplier?->email) }}" maxlength="255"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea id="address" name="address" rows="3" maxlength="2000"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $supplier?->address) }}</textarea>
        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
        <textarea id="notes" name="notes" rows="3" maxlength="2000"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $supplier?->notes) }}</textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
    </div>
</form>
