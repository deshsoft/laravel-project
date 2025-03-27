<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="p-6 text-gray-900">
                <h2 class="text-xl font-bold mb-4">Booking Events Calendar</h2>
                <div id="calendar"></div>
            </div>
        </div>
    </div>


    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("FullCalendar script loaded"); // Debugging

            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error("Calendar container not found!");
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Default to Monthly View
                headerToolbar: {
                    left: 'prev,next today', // Navigation Buttons
                    center: 'title', // Title of the Calendar
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Monthly, Weekly, Daily Views
                },
                selectable: true,
                editable: false,
                events: "{{ route('calendar.events') }}", // Fetch Events via API
                eventClick: function (info) {
                    alert('Event: ' + info.event.title);
                }
            });

            calendar.render();
            console.log("Calendar initialized successfully"); // Debugging
        });
    </script>
    <style>
        /* Ensure event titles wrap inside FullCalendar boxes */
        .fc-daygrid-event {
            white-space: normal !important;
            /* Allows text wrapping */
            height: auto !important;
            /* Ensures the box expands */
        }

        .fc-daygrid-event .fc-event-title {
            white-space: normal !important;
            /* Allows long text to wrap */
            overflow: visible !important;
            /* Ensures text is fully visible */
            display: block !important;
            /* Ensures proper wrapping */
            font-size: 12px;
            /* Adjust font size if needed */
        }

        /* Adjust event container for proper display */
        .fc-daygrid-event-harness {
            height: auto !important;
        }

        /* Prevent text from getting cut off */
        .fc-event {
            overflow-wrap: break-word !important;
            /* Ensures words wrap properly */
        }
    </style>



</x-app-layout>