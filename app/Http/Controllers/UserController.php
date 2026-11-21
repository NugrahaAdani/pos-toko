<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return $this->unavailable();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->unavailable();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        return $this->unavailable();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user): Response
    {
        return $this->unavailable();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $user): Response
    {
        return $this->unavailable();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user): Response
    {
        return $this->unavailable();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user): Response
    {
        return $this->unavailable();
    }

    private function unavailable(): Response
    {
        return response('Pengelolaan akun kasir belum dibuat.', Response::HTTP_NOT_IMPLEMENTED);
    }
}
