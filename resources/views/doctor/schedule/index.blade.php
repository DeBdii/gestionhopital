@extends('layouts.appp')

@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>

    <!-- Include FullCalendar CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<script>
    $(document).ready(function() {
        var shifts = @json($shifts);
        var userShifts = @json($userShifts);
        var appointments = @json($appointments);

        var events = appointments.map(function(appointment) {
            var start = moment(appointment.appointment_date);
            var end = start.clone().add(30, 'minutes');
            return {
                id: appointment.id,
                title: 'Appointment with ' + appointment.patient.name,
                start: start.format(),
                end: end.format(),
                color: '#810404' // Color for appointments
            };
        });

        var backgroundEvents = [];
        var shiftDates = [];

        // Add background events for shifts with calculated time duration
        shifts.forEach(function(shift) {
            if (userShifts.includes(shift.id)) {
                var start = moment(shift.start_datetime);
                var end = moment(shift.end_datetime);
                var duration = moment.duration(end.diff(start));
                var durationHours = duration.hours();
                var durationMinutes = duration.minutes();

                while (start.isBefore(end)) {
                    var dayStart = start.clone().startOf('day').add(start.hours(), 'hours').add(start.minutes(), 'minutes');
                    var dayEnd = dayStart.clone().add(durationHours, 'hours').add(durationMinutes, 'minutes');

                    backgroundEvents.push({
                        start: dayStart.format(),
                        end: dayEnd.format(),
                        rendering: 'background',
                        color: '#00FF00' // Color for the background shifts
                    });

                    shiftDates.push(dayStart.format('YYYY-MM-DD')); // Collect the shift date

                    start.add(1, 'days');
                }
            }
        });

        $('#calendar').fullCalendar({
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: events.concat(backgroundEvents), // Combine regular events with background events
            selectable: true,
            selectHelper: true,
            allDaySlot: false,
            dayClick: function(date, jsEvent, view) {
                if (view.name === 'month') {
                    $('#calendar').fullCalendar('changeView', 'agendaDay', date);
                }
            },
            dayRender: function(date, cell) {
                if (shiftDates.includes(date.format('YYYY-MM-DD'))) {
                    cell.css("background-color", '#00FF00'); // Green color for the shift days
                }
            },
            eventResize: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: { title: title, start: start, end: end, id: id },
                    success: function() {
                        $('#calendar').fullCalendar('refetchEvents');
                        alert('Event Updated');
                    }
                });
            },
            eventDrop: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: { title: title, start: start, end: end, id: id },
                    success: function() {
                        $('#calendar').fullCalendar('refetchEvents');
                        alert("Event Updated");
                    }
                });
            },
            eventClick: function(event) {
                if (confirm("Are you sure you want to remove it?")) {
                    var id = event.id;
                    $.ajax({
                        url: "delete.php",
                        type: "POST",
                        data: { id: id },
                        success: function() {
                            $('#calendar').fullCalendar('refetchEvents');
                            alert("Event Removed");
                        }
                    });
                }
            }
        });
    });
</script>
@endsection
