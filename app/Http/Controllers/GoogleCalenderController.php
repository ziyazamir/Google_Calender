<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;
use stdClass;

// use Illuminate\Http\Request;

class GoogleCalenderController extends Controller
{
    protected $client;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            // print_r($_SESSION['access_token']);
            // die();
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);
            // die($results);
            $event = array_reverse($results->getItems());
            $data = new stdClass;
            $data->data = "hello";
            return view('index', ['events' => json_encode($event)]);
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function oauth()
    {
        session_start();

        $rurl = action([GoogleCalenderController::class, 'oauth']);
        // die($rurl);
        $this->client->setRedirectUri($rurl);
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            echo "helllo";
            return redirect()->route('index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        session_start();
        $startDateTime = $request->start_date;
        $endDateTime = $request->end_date;

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $event = new Google_Service_Calendar_Event(array(
                'summary' => $request->title,
                'location' => '800 Howard St., San Francisco, CA 94103',
                'description' => $request->description,
                'start' => array(
                    'date' => $startDateTime,
                    'timeZone' => 'America/Los_Angeles',
                ),
                'end' => array(
                    'date' => $endDateTime,
                    'timeZone' => 'America/Los_Angeles',
                ),
                'recurrence' => array(
                    'RRULE:FREQ=DAILY;COUNT=2'
                ),
                'attendees' => array(
                    array('email' => 'lpage@example.com'),
                    array('email' => 'sbrin@example.com'),
                ),
                'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 10),
                    ),
                ),
            ));

            $calendarId = 'primary';
            $event = $service->events->insert($calendarId, $event);
            printf('Event created: %s\n', $event->htmlLink);
            if (!$event) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return redirect()->route('index')->with('success', "created succesfully");
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($eventId)
    {
        return null;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $eventId)
    {
        // update 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($id)
    {
        session_start();
        // echo $id;
        // die();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $service->events->delete('primary', $id);
            return redirect()->route('index')->with('delete', "deleted succesfully");
            // echo $result;
            die();
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        // echo "destroyed";
        return redirect('/');
        // echo $_SESSION['access_token'];
    }
}
