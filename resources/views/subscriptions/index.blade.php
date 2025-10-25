@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Choisissez votre abonnement</h1>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Plan Mensuel -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Plan Mensuel</h2>
            <p class="text-3xl font-bold mb-4">5000 FCFA<span class="text-sm text-gray-600">/mois</span></p>
            <ul class="mb-6 space-y-2">
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Accès à tous les articles</li>
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Support basique</li>
            </ul>
            <button onclick="showPaymentForm('mensuel', 5000)" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Choisir</button>
        </div>

        <!-- Plan Trimestriel -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-blue-500">
            <div class="absolute top-0 right-0 bg-blue-500 text-white px-2 py-1 text-sm rounded-bl">Popular</div>
            <h2 class="text-xl font-bold mb-4">Plan Trimestriel</h2>
            <p class="text-3xl font-bold mb-4">12000 FCFA<span class="text-sm text-gray-600">/3 mois</span></p>
            <ul class="mb-6 space-y-2">
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Accès à tous les articles</li>
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Support prioritaire</li>
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>-20% de réduction</li>
            </ul>
            <button onclick="showPaymentForm('trimestriel', 12000)" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Choisir</button>
        </div>

        <!-- Plan Annuel -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Plan Annuel</h2>
            <p class="text-3xl font-bold mb-4">40000 FCFA<span class="text-sm text-gray-600">/an</span></p>
            <ul class="mb-6 space-y-2">
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Accès à tous les articles</li>
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Support VIP</li>
                <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>-40% de réduction</li>
            </ul>
            <button onclick="showPaymentForm('annuel', 40000)" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Choisir</button>
        </div>
    </div>

    <!-- Modal de paiement -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="bg-white p-8 max-w-md mx-auto mt-20 rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Formulaire de paiement</h2>
            <form id="paymentForm" method="POST" action="{{ route('subscriptions.subscribe') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="plan_type" id="plan_type">
                <input type="hidden" name="amount" id="amount">

                <div>
                    <label class="block text-gray-700 mb-2">Mode de paiement</label>
                    <select name="payment_method" class="w-full p-2 border rounded" required>
                        <option value="orange">Orange Money</option>
                        <option value="mtn">MTN Mobile Money</option>
                        <option value="moov">Moov Money</option>
                        <option value="wave">Wave</option>
                        <option value="test">Mode Test (Simulation)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Numéro de téléphone</label>
                    <input type="tel" name="phone" class="w-full p-2 border rounded" required pattern="[0-9]{10}" placeholder="0101234567">
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Payer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showPaymentForm(planType, amount) {
    document.getElementById('plan_type').value = planType;
    document.getElementById('amount').value = amount;
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
</script>
@endpush
@endsection
