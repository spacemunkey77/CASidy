<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>CASidy :: Status</title>
  <link href="{{ asset('css/status.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <img src="/images/svg/blank.svg" width="30" height="30" class="d-inline-block align-top" alt="">
        CASidy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/status">Status</a>
                </li>
                @can('condo_settings')
                <li class="nav-item">
                    <a class="nav-link" href="/sensors/setup">Security</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/setup">Settings</a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>                    
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-sm-4 mb-3 mb-sm-0">
                @can('view_alarm_entries')
                <div class="card" style="width: 18rem; height: 21rem;">
                    <img src="/images/svg/blank.svg" height="100px" class="card-img-top alarmstatus" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Alarm Status</h5>
                        <p class="card-text"><span class="paddy-one">Armed:</span> <span class="armtime"></span><br>
                        <span class="paddy-one">Disarmed:</span> <span class="disarmtime"></span><br>
                        <span class="alarmevent"></span></p>
                    </div>
                </div>
                @endcan
            </div>
            <div class="col-sm-4">
                @can('view_door_entries')
                <div class="card" style="width: 18rem; height: 21rem;">
                    <img src="/images/svg/blank.svg" height="100px" class="card-img-top frontdoor" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Front Door</h5>
                        <p class="card-text mb-0 doorinfo"></p>
                        <table>
                            <col width="25px">
                            <tr>
                                <td><img src="/images/svg/batterylevel.svg" height="20px"></td>
                                <td class="battery">
                                    <div>
                                        <div class="battpercent"></div>
                                        <div class="bar">&nbsp;</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        @can('can_operate_lock')
                        <p class="lock-operations"><a href="#" onclick="javascript:doorMode('unlock')"><img src="/images/openhouseicon.jpg"></a>&emsp;&emsp;
                            <a href="#" onclick="javascript:doorMode('lock')"><img src="/images/lockhouseicon.jpg"></a></p>
                        @endcan
                    </div>
                </div>
                @endcan
            </div>
            <div class="col-sm-4">
                <div class="card" style="width: 18rem; height: 21rem;">
                    <img src="/images/svg/lightbulb.svg" height="100px" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Lights</h5>
                        <p class="card-text"><span class="paddy-two"><img src="/images/svg/sunset.svg" height="20px"></span><span class="sunsettime"></span><br>
                          <span class="paddy-two"><img src="/images/svg/lighton.svg" height="20px"></span><span class="lightson"></span><br>
                          <span class="paddy-two"><img src="/images/svg/lightoff.svg" height="20px"></span><span class="lightsoff"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-sm-4 mb-3 mb-sm-0">     
                <div class="card" style="width: 18rem; height: 21rem;">
                    <img src="/images/svg/blank.svg" height="100px" class="card-img-top thermostat" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Nest Thermostat</h5>
                        <p class="card-text">
                          <span class="paddy-two"><img src="/images/svg/environment.svg" height="20px"></span><span class="outdoorfahr"></span><br>
                          <span class="paddy-two"><img src="/images/svg/indoorfahr.svg" height="20px"></span><span class="ambient"></span><br>
                          <span class="paddy-two"><img src="/images/svg/thermtemp.svg" height="20px"></span><span class="settemp"></span><br>
                          <span class="paddy-two"><img src="/images/svg/humidity.svg" height="20px"></span><span class="humidity"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="width: 18rem; height: 21rem;">
                    <img src="/images/svg/status.svg" height="100px" class="card-img-top" alt="...">
                    <div class="card-body status-links">
                        @can('view_door_entries')
                        <div class="frontdoorlink"></div>
                        <div class="doorbelllink"></div>
                        @endcan
                        @can('view_boundary_entries')
                        <div class="boundarylink"></div>
                        @endcan
                        @can('view_alarm_entries')
                        <div class="alarmlink"></div>
                        <div>
                            <a target="_blank" href="http://konnected.universe.local:14984">Konnected.io Board Status</a>
                        </div>
                        @endcan
                        @can('view_power_status')
                        <a href="/status/power">View UPS Status</a>
                        @endcan                                     
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/status.js') }}"></script>

</body>
</html>
