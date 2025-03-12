<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Calendario Appuntamenti</h2>

                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

    <!-- FullCalendar JS + Axios -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            axios.defaults.withCredentials = true;
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const calendarEl = document.getElementById('calendar');

            // ✅ Informazione sul ruolo
            const isAdmin = @json(auth()->user()->role === 'admin');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'it',
                timeZone: 'Europe/Rome',
                initialView: 'timeGridWeek',
                firstDay: 1,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',

                editable: isAdmin,
                selectable: isAdmin,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                events: async function(fetchInfo, successCallback, failureCallback) {
                    try {

                        let response;
                        let events = [];

                        if (isAdmin) {
                            // ✅ ADMIN: tutti gli appuntamenti
                            response = await axios.get('/api/appointments');

                            events = response.data.map(event => ({
                                id: event.id,
                                title: `${event.title} (${event.user_name ?? ''})`,
                                start: event.start,
                                end: event.end,
                                color: event.color ?? '#3788d8'
                            }));

                        } else {
                            // ✅ CLIENTE: solo gli slot disponibili
                            response = await axios.get('/api/available-slots');

                            events = response.data.map(slot => ({
                                id: `slot-${slot.start}`,
                                title: 'Disponibile',
                                start: slot.start,
                                end: slot.end,
                                color: '#28a745'
                            }));
                        }

                        successCallback(events);

                    } catch (error) {
                        console.error('Errore nel recupero eventi/slot:', error);
                        failureCallback(error);
                    }
                },

                eventClick: async function(info) {

                    // ✅ Se sei ADMIN
                    if (isAdmin) {
                        if (confirm(`Vuoi eliminare l'appuntamento: "${info.event.title}"?`)) {
                            try {
                                await axios.delete(`/api/appointments/${info.event.id}`);
                                calendar.refetchEvents();
                            } catch (error) {
                                console.error('Errore nell\'eliminazione evento:', error);
                            }
                        }

                    } else {
                        // ✅ Se sei CLIENTE e clicchi uno slot disponibile
                        const startDate = new Date(info.event.startStr);
                        const endDate = new Date(info.event.endStr);

                        const options = {
                            timeZone: 'Europe/Rome',
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit'
                        };

                        const startFormatted = startDate.toLocaleString('it-IT', options);
                        const endFormatted = endDate.toLocaleString('it-IT', options);

                        const conferma = confirm(`Vuoi prenotare questo appuntamento?\n${startFormatted} - ${endFormatted}`);
                        if (!conferma) return;

                        try {
                            await axios.post('/api/appointments', {
                                title: 'Appuntamento Prenotato da {{ auth()->user()->name }}',
                                start: info.event.startStr,
                                end: info.event.endStr
                            });

                            alert('Prenotazione completata!');
                            calendar.refetchEvents();

                        } catch (error) {
                            alert('Errore nella prenotazione.');
                            console.error(error);
                        }
                    }

                },

                // ✅ ADMIN: crea un appuntamento selezionando una fascia
                select: async function(info) {
                    if (!isAdmin) return;

                    const title = prompt('Titolo dell\'appuntamento');
                    if (!title) {
                        calendar.unselect();
                        return;
                    }

                    try {
                        await axios.post('/api/appointments', {
                            title: title,
                            start: info.startStr,
                            end: info.endStr
                        });

                        calendar.refetchEvents();
                    } catch (error) {
                        console.error('Errore nella creazione appuntamento:', error);
                    }

                    calendar.unselect();
                },

                // ✅ ADMIN: sposta appuntamento con drag & drop
                eventDrop: async function(info) {
                    if (!isAdmin) return;

                    try {
                        await axios.put(`/api/appointments/${info.event.id}`, {
                            start: info.event.startStr,
                            end: info.event.endStr
                        });

                        calendar.refetchEvents();
                    } catch (error) {
                        console.error('Errore nello spostamento appuntamento:', error);
                    }
                },

                // ✅ ADMIN: ridimensiona la durata di un appuntamento
                eventResize: async function(info) {
                    if (!isAdmin) return;

                    try {
                        await axios.put(`/api/appointments/${info.event.id}`, {
                            start: info.event.startStr,
                            end: info.event.endStr
                        });

                        calendar.refetchEvents();
                    } catch (error) {
                        console.error('Errore nel ridimensionamento appuntamento:', error);
                    }
                }

            });

            calendar.render();
        });
    </script>

</x-app-layout>
