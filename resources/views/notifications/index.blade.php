@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Notifications</h1>

    @if($notifications->count() > 0)
        <div class="bg-white rounded-lg shadow">
            @foreach($notifications as $notification)
                <div class="p-4 border-b {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                @if(isset($notification->data['titre']))
                                    Nouvel article : {{ $notification->data['titre'] }}
                                @else
                                    Nouvelle notification
                                @endif
                            </h3>
                            <p class="text-gray-600 mt-1">
                                @if(isset($notification->data['category']))
                                    Dans la catégorie : {{ $notification->data['category'] }}
                                @endif
                            </p>
                            <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    Marquer comme lu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-600">Vous n'avez pas de notifications.</p>
        </div>
    @endif
</div>
@endsection
