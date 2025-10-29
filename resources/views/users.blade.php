<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-gray-50 min-h-screen text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <header class="mb-6">
            <h1 class="text-2xl font-semibold">Users</h1>
            <p class="text-sm text-gray-500">List of users with their online status and status code</p>
        </header>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <ul class="divide-y">
                @forelse($users as $user)
                    @php
                        // boolean-ish flag; adapt to your model (is_online, online, last_seen, etc.)
                        $online = !empty($user->is_online);
                        // numeric status code (adjust semantics as you prefer)
                        $statusCode = $online ? 1 : 0;
                    @endphp

                    <li class="flex items-center px-5 py-4">
                        <!-- avatar / initials -->
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($user->name ?? ($user->email ?? 'U'), 0, 1)) }}
                            </div>
                        </div>

                        <!-- main info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name ?? 'Unknown User' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $user->email ?? '—' }}</p>
                                </div>

                                <!-- status badge -->
                                <div class="ml-4 flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $online ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        <span class="inline-block w-2 h-2 mr-2 rounded-full {{ $online ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $online ? 'Online' : 'Offline' }}
                                    </span>

                                    <span class="text-xs text-gray-500">code: <span class="font-medium text-gray-700 ml-1">{{ $statusCode }}</span></span>
                                </div>
                            </div>

                            @if(!$online)
                                <p class="mt-2 text-xs text-gray-400">
                                    Last seen:
                                    {{ optional($user->last_seen)->diffForHumans() ?? 'Unknown' }}
                                </p>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-gray-500">
                        No users found.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html>