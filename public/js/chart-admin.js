// Chart.js admin dashboard charts
window.addEventListener('DOMContentLoaded', function () {
    // Pie chart Utilisateurs/Abonnés
    if (document.getElementById('usersPieChart')) {
        const ctx = document.getElementById('usersPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: window.usersPieData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Répartition Utilisateurs / Abonnés' }
                }
            }
        });
    }
    // Bar chart utilisateurs connectés
    if (document.getElementById('usersBarChart')) {
        const ctx2 = document.getElementById('usersBarChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: window.usersBarData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Utilisateurs connectés par jour' }
                },
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Nb connectés' }, beginAtZero: true }
                }
            }
        });
    }
});
