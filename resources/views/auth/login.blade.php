<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
    <main class="w-full max-w-sm rounded-lg bg-white p-8 shadow-sm">
        <h1 class="mb-6 text-center text-xl font-semibold">POS Barokah Mart</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-100 p-3 text-sm text-red-700" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
            </div>
            <button type="submit" class="w-full rounded-md bg-indigo-600 py-2 text-white hover:bg-indigo-700">
                Masuk
            </button>
        </form>
    </main>
</body>
</html>
