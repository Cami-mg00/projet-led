document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Type d'affichage du calendrier (par mois)
        events: '/index.php?controller=event&action=getEvents', // URL pour récupérer les événements
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url; // Redirige l'utilisateur vers la page de l'événement
            }
        }
    });
    calendar.render(); // Affiche le calendrier
});
