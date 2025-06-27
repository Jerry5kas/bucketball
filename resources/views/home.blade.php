<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bucket Ball System</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-3xl mx-auto bg-white shadow-lg p-6 rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Bucket & Ball Volume Suggestion</h1>

    <form action="{{ route('suggest') }}" method="POST" class="space-y-4">
        @csrf
        @foreach ($balls as $ball)
            <div>
                <label class="block font-semibold">{{ $ball->color }} Balls</label>
                <input type="number" name="balls[{{ $ball->color }}]" class="w-full border rounded px-4 py-2 mt-1" min="0" placeholder="Enter quantity">
            </div>
        @endforeach

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>
    </form>

    @if (session('suggestions'))
        <div class="mt-6">
            <h2 class="text-xl font-semibold">Suggestions:</h2>
            <ul class="mt-2 space-y-2">
                @foreach (session('suggestions') as $s)
                    <li class="border p-2 rounded bg-gray-50">
                        @if ($s['bucket'])
                            Place {{ $s['quantity'] }} {{ $s['color'] }} ball(s) in Bucket {{ $s['bucket'] }}
                        @else
                            Not enough space for {{ $s['quantity'] }} {{ $s['color'] }} ball(s)
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('reset.volumes') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Reset Bucket Volumes
        </button>
    </form>

</div>
</body>
</html>
