<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    public function index(): Response
    {
        $suppliers = Supplier::query()
            ->withCount('purchaseOrders')
            ->orderBy('name')
            ->paginate(10);

        return response()->view('suppliers.index', compact('suppliers'));
    }

    public function create(): Response
    {
        return response()->view('suppliers.create');
    }

    public function store(Request $request): Response
    {
        Supplier::create($this->validatedData($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier): Response
    {
        $purchaseOrders = $supplier->purchaseOrders()
            ->with('user')
            ->latest('purchased_at')
            ->paginate(10);

        return response()->view('suppliers.show', compact('supplier', 'purchaseOrders'));
    }

    public function edit(Supplier $supplier): Response
    {
        return response()->view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): Response
    {
        $supplier->update($this->validatedData($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): Response
    {
        if ($supplier->purchaseOrders()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat pembelian.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }

    /**
     * @return array{name: string, contact_person: ?string, phone: ?string, email: ?string, address: ?string, notes: ?string}
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
