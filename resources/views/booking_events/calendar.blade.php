<x-app-layout>
    <div class="container-fluid mx-auto p-4">
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
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error("Calendar container not found!");
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable: true,
                editable: false,
                events: function (fetchInfo, successCallback, failureCallback) {
                    fetch("{{ route('booking-events.events') }}")
                        .then(response => response.json())
                        .then(events => {
                            events = events.map(event => {
                                return {
                                    ...event,
                                    classNames: event.is_done === 1 ? "event-complete" : "event-incomplete"
                                };
                            });
                            successCallback(events);
                        })
                        .catch(error => failureCallback(error));
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false // Uses 24-hour format
                },
                eventClick: function (info) {
                    window.location.href = "{{ url('booking-events') }}/" + info.event.id + "/view";
                },
                dateClick: function (info) {
                    const selectedDate = info.dateStr;
                    window.location.href = "{{ url('booking-events/create') }}?date=" + selectedDate;
                }
            });

            calendar.render();
        });
    </script>

    <style>
        /* Remove Default Event Dot */
        .fc-daygrid-event-dot {
            display: none !important;
        }

        /* General Event Style (Uncompleted - Normal Google Calendar Style) */
        .fc-daygrid-event,
        .fc-timegrid-event {
            display: block !important;
            padding: 5px;
            border-left: 5px solid #28a745 !important;
            /* Default Green for Incomplete */
            background-color: #d4edda !important;
            /* Light Green Background */
            color: #155724 !important;
            /* Dark Green Text (Same as Monthly View) */
            font-size: 12px;
            font-weight: bold !important;
            text-align: left !important;
            /* Ensure Left Alignment */
            white-space: normal !important;
            overflow-wrap: break-word !important;
            /* Prevents text from cutting off */
        }

        /* Google Calendar Completed Event Style */
        .event-complete {
            border-left: 5px solid #6c757d !important;
            /* Gray Left Bar */
            background-color: #e9ecef !important;
            /* Light Gray Background */
            color: #6c757d !important;
            /* Dark Gray Text */
            text-decoration: line-through !important;
            /* Strikethrough Title */
        }

        /* Incomplete Events - Normal Green Style */
        .event-incomplete {
            border-left: 5px solid #28a745 !important;
            /* Green Left Bar */
            background-color: #d4edda !important;
            /* Light Green Background */
            color: #155724 !important;
            /* Dark Green Text */
        }

        /* Ensure Time is on the First Line */
        .fc-event-time {
            display: block !important;
            font-weight: bold !important;
            margin-bottom: 2px;
            color: inherit !important;
            /* Ensures the text color is inherited correctly */
        }

        /* Ensure Title is on the Second Line */
        .fc-event-title {
            display: block !important;
            white-space: normal !important;
            overflow-wrap: break-word !important;
            color: inherit !important;
            /* Ensures the text color is inherited correctly */
        }

        /* Fix Text Visibility in Weekly & Daily Views */
        .fc-timegrid-event,
        .fc-timegrid-event-harness {
            opacity: 1 !important;
            /* Ensure full visibility */
            background-color: #d4edda !important;
            /* Light Green Background */
            color: #155724 !important;
            /* Dark Green Text (Matches Monthly View) */
            font-weight: bold !important;
            /* Make sure text is bold in all views */
        }

        /* Ensure Only One Left Green Bar is Applied in All Views */
        .fc-daygrid-event,
        .fc-timegrid-event {
            border-left: 5px solid #28a745 !important;
            /* Single Green Left Bar */
        }

        /* Ensure Completed Events Stay Consistent in All Views */
        .fc-timegrid-event.event-complete,
        .fc-daygrid-event.event-complete {
            border-left: 5px solid #6c757d !important;
            /* Gray Left Bar */
            background-color: #e9ecef !important;
            /* Light Gray Background */
            color: #6c757d !important;
            /* Dark Gray Text */
            text-decoration: line-through !important;
            /* Strikethrough */
        }

        /* Ensure text alignment in Weekly & Daily Views */
        .fc-timegrid-event .fc-event-title-container {
            display: block !important;
            text-align: left !important;
            /* Ensures Left Alignment */
        }

        /* Fix Weekly & Daily View Missing Text Color */
        .fc-timegrid-event .fc-event-time,
        .fc-timegrid-event .fc-event-title {
            color: #155724 !important;
            /* Dark Green Text Same as Monthly View */
        }
    </style>
</x-app-layout>