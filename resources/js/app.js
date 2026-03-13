import './bootstrap';

// Importez SweetAlert2 pour de belles alertes
import Swal from 'sweetalert2';
window.Swal = Swal;

// Importez Chart.js pour les graphiques
import Chart from 'chart.js/auto';
window.Chart = Chart;

// Scripts personnalisés
document.addEventListener('DOMContentLoaded', function() {
    // Initialisez les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            bootstrap.Alert.getInstance(alert)?.close();
        });
    }, 5000);
    
    // Filtres interactifs
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Charts pour les statistiques
    if (document.getElementById('congesChart')) {
        const ctx = document.getElementById('congesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Congés approuvés',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }
});