<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('create') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="sdfas" class="form-label">Description</label>
                            <input name="description" type="text" class="form-control" id="sdfas"
                                aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Start Date</label>
                            <input name="start_date" type="date" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3">
                            <label for="enddate" class="form-label">End Date</label>
                            <input name="end_date" type="date" class="form-control" id="enddate">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>
    <h1>Hello, world!</h1>
    <div class="container p-5">
        <div id='calendar'></div>

        <div class="container-fluid">
            <h1>All Events</h1>
            <div class="row">
                @foreach (json_decode($events) as $event)
                    {{-- <p>{{ $event->summary }}</p> --}}
                    <div class="col-md-4 p-2">
                        <div class="card">
                            <div class="card-block p-3">
                                <h4 class="card-title">{{ $event->summary }}</h4>
                                <h6 class="card-subtitle text-muted">From {{ $event->start->date }} to
                                    {{ $event->end->date }}</h6>
                                <p class="card-text p-y-1">{{ $event->description }}</p>
                                <p class="">Organizer {{ $event->organizer->email }}</p>
                                <a href="{{ route('destroy', [$event->id]) }}" class="card-link">Delete</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><br>

        </div>
    </div>
    <div style="position: fixed; bottom: 20px; right: 20px;z-index:1000">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">create new
            event</button>
        <div style="position: fixed; top: 20px; right: 20px;z-index:1000">
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
        </div>
        {{-- {{ ($events) }} --}}

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
        @php
            echo '<script>
                var events = ' .$events.'
            </script>';
        @endphp
        <script>
            var sites = '{{ $events }}';
            let all_events = [];

            // var events = JSON.parse(sites.replace(/&quot;/g, '"'));
            console.log(events);
            events.forEach(element => {
                let item = {};
                item.title = element.summary;
                item.start = element.start.date;
                item.end = element.start.date;
                item.allday = false;
                all_events.push(item);
            });
            console.log(all_events);
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: all_events,
                    headerToolbar: {
                        start: 'prev,next today',
                        center: 'title',
                        end: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                });
                calendar.render();
            });
        </script>
</body>

</html>
@if ($message = Session::get('success'))
    <script>
        Swal.fire({
            title: 'Success',
            text: 'Event added succesfully',
            icon: 'success',
            confirmButtonText: 'Cool'
        })
    </script>
@endif
@if ($message = Session::get('delete'))
    <script>
        Swal.fire({
            title: 'Success',
            text: 'Event Deletd succesfully',
            icon: 'error',
            confirmButtonText: 'Cool'
        })
    </script>
@endif
