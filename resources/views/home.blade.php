<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bucket Ball System</title>
    <!-- Inside <head> -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
    </style>

    @vite('resources/css/app.css')
</head>
<body class="font-montserrat bg-slate-900 p-6">
<div class="max-w-6xl mx-auto bg-transparent shadow-lg p-6 rounded-xl">
    <h1 class="w-full text-center text-4xl font-extrabold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 drop-shadow-md tracking-wide">
        Bucket & Ball Volume Suggestion
    </h1>


    <div class="w-full mb-6 font-montserrat">
        <div class="flex justify-between items-center pb-8">
            <div class="text-xl text-white font-semibold mb-2">Bucket Status</div>
            @if (session('success'))
                <div class="bg-green-800/80 text-white p-2 mb-4 rounded font-mono ">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('reset.volumes') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to reset all bucket volumes?');" class="mt-4">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-300 transform hover:scale-105 font-semibold font-montserrat">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582M20 20v-5h-.581M9 19A7 7 0 0012 5a7 7 0 011.09 13.888"/>
                    </svg>
                    Reset Bucket Volumes
                </button>


            </form>
        </div>

        <div class="grid grid-cols-2 gap-6 mt-8 text-white">
            <!-- Add Bucket Form -->
            <form action="{{ route('buckets.store') }}" method="POST" class="bg-slate-800 p-4 rounded-xl space-y-4">
                @csrf
                <h2 class="text-lg font-bold">Add New Bucket</h2>
                <input name="name" type="text" placeholder="Bucket Name" class="w-full px-4 py-2 border border-white rounded text-white" required>
                <input name="total_volume" type="number" step="0.1" placeholder="Total Volume" class="w-full px-4 py-2  border border-whiterounded text-white" required>
                <button type="submit" class="bg-blue-600 px-4 py-2 rounded text-white border border-white hover:bg-blue-700">Add Bucket</button>
            </form>

            <!-- Add Ball Form -->
            <form action="{{ route('balls.store') }}" method="POST" class="bg-slate-800 p-4 rounded-xl space-y-4">
                @csrf
                <h2 class="text-lg font-bold">Add New Ball</h2>
                <input name="color" type="text" placeholder="Ball Color" class="w-full px-4 py-2 rounded border border-white text-white" required>
                <input name="volume" type="number" step="0.1" placeholder="Volume" class="w-full px-4 py-2 rounded border border-white text-white" required>
                <button type="submit" class="bg-green-600 px-4 py-2 rounded text-white border border-white hover:bg-green-700">Add Ball</button>
            </form>
        </div>

        <div class="grid grid-cols-5 md:grid-cols-5 gap-4 py-5 ">
            @php
                // Define a set of color classes
                $colors = ['text-red-700', 'text-green-700', 'text-blue-700', 'text-yellow-700', 'text-purple-700', 'text-pink-700', 'text-indigo-700'];
            @endphp

            @foreach ($buckets as $index => $bucket)
                @php
                    // Ensure fallback if $colors is empty
                    $colorClass = $colors && count($colors) > 0
                        ? $colors[$index % count($colors)]
                        : 'bg-gray-100';
                @endphp

                <div class="p-4 border rounded bg-white text-sm space-y-2 text-slate-900">
                    <div class="inline-flex items-center justify-center text-lg space-x-2">
                        <x-icons.bucket class="h-24 w-auto " :color-class="$colorClass"  />
                        <strong>Bucket {{ $bucket->name }}</strong>
                    </div>
                    <div class="text-gray-700 font-semibold">Total Volume: <span class="{{$colorClass}}">{{ $bucket->total_volume }}</span> in³</div>
                    <div class="text-gray-700 font-semibold">Empty Volume: <span class="{{$colorClass}}">{{ $bucket->empty_volume }}</span> in³</div>
                </div>
            @endforeach
        </div>
    </div>


    <div class="flex justify-between items-center gap-6">
    <form action="{{ route('suggest') }}" method="POST" class="w-2/3 space-y-4 bg-white rounded-2xl p-5">
        @csrf
        @foreach ($balls as $ball)
            <div>
                <label class="block font-semibold">
                    {{ $ball->color }} Balls
                    <span class="text-sm text-gray-500">(Volume: {{ $ball->volume }} in³)</span>
                </label>
                <input type="number" name="balls[{{ $ball->color }}]" class="w-full border rounded px-4 py-2 mt-1"
                       min="0" placeholder="Enter quantity">
            </div>
        @endforeach


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>
    </form>
        @if (session('suggestions'))
            <div class="mt-6">
                <h2 class="text-xl text-white font-semibold">Suggestions:</h2>
                <ul class="mt-2 space-y-2">
                    @foreach (session('suggestions') as $s)
                        <li class="border p-2 rounded bg-gray-50">
                            @if ($s['bucket'])
                                <span class="text-green-700 bg-green-100 px-2 py-1 rounded">
        ✅ Place {{ $s['quantity'] }} {{ $s['color'] }} ball(s) in Bucket {{ $s['bucket'] }}
    </span>
                            @else
                                <span class="text-red-700 bg-red-100 px-2 py-1 rounded">
        ❌ Not enough space for {{ $s['quantity'] }} {{ $s['color'] }} ball(s)
    </span>
                            @endif

                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>




</div>
</body>
</html>
