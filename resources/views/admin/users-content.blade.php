<div class="p-6 md:p-10 bg-gradient-to-br from-blue-50 via-white to-indigo-100 min-h-screen">
    <h1 class="text-3xl md:text-4xl font-extrabold text-indigo-700 mb-8 flex items-center gap-3">
        <span class="inline-block bg-indigo-100 text-indigo-600 rounded-full p-2 shadow-md">👥</span>
        Utilisateurs
    </h1>
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white font-semibold rounded-full shadow hover:bg-indigo-700 transition gap-2 text-base">
                <span class="text-xl">➕</span> Ajouter un utilisateur
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-indigo-100 overflow-x-auto">
            <table class="min-w-full divide-y divide-indigo-50">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Prénom</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="hover:bg-indigo-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-700">{{ $user->id }}</td>
                            <td class="px-4 py-3 flex items-center gap-2">
                                <span class="inline-block h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">{{ strtoupper(substr($user->firstname ?? $user->name,0,1)) }}</span>
                                <span>{{ $user->firstname ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $user->lastname ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 flex gap-3">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition gap-2 shadow" title="Voir">
                                    <span class="text-lg">👁️</span> <span>Voir</span>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition gap-2 shadow" title="Modifier">
                                    <span class="text-lg">✏️</span> <span>Éditer</span>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition gap-2 shadow" title="Supprimer">
                                        <span class="text-lg">🗑️</span> <span>Supprimer</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Aucun utilisateur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
