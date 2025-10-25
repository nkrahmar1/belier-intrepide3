@props(['activities' => []])
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
    <ul class="list-group list-group-flush">
        @forelse($activities as $activity)
            <li class="list-group-item d-flex align-items-start gap-3">
                <span class="badge bg-primary rounded-pill mt-1"><i class="fas fa-bolt"></i></span>
                <div class="flex-fill">
                    <div class="fw-semibold text-dark">{{ $activity->description }}</div>
                    <div class="text-muted small">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            </li>
        @empty
            <li class="list-group-item text-center text-muted py-4">Aucune activité récente</li>
        @endforelse
    </ul>
</div>
