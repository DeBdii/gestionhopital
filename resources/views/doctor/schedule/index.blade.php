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

            var events = shifts.map(function(shift) {
                var isUserShift = userShifts.includes(shift.id);
                var color = isUserShift ? 'green' : 'grey'; // Color logic

                return {
                    id: shift.id,
                    title: shift.shift_name,
                    start: shift.start_datetime,
                    end: shift.end_datetime,
                    color: color
                };
            });

            $('#calendar').fullCalendar({
                editable: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: events,
                selectable: true,
                selectHelper: true,
                allDaySlot: false,
                dayClick: function(date, jsEvent, view) {
                    if (view.name === 'month') {
                        $('#calendar').fullCalendar('changeView', 'agendaDay', date);
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
