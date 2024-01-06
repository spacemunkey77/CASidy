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
                <img src="/images/{{$brandicon}}" width="30" height="30" class="d-inline-block align-top" alt="">
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
            <table class="table table-striped">
                <col width="40%">
                @can('view_alarm_entries')
                <tr>
                    <th>🚨 Alarm Last Armed:</th>
                    <td>{{$status['armed']}}</td>
                </tr>
                <tr>
                    <th>🚨 Alarm Last Disarmed:</th>
                    <td>{{$status['disarmed']}}</td>
                </tr>
                @endcan
                @can('view_door_entries')
                @if ($count['entries'] > 0)
                <tr>
                    <th>🚪 Last Entry</th>
                    <td>{{$status['door']}}</td>
                </tr>
                @endif
                <tr>
                    <th>🔑 Door Lock</th>
                    <td>{{$status['augustlock']['doorBolt']}}</td>
                </tr>
                <tr>
                    <th>🪫 Battery Level</th>
                    <td class="battery">
                        <div>
                            <div class="battpercent">
                                {{ $status['augustlock']['battery'] }} %
                            </div>
                            <div class="bar" style="width: {{ $status['augustlock']['battery'] }}%;background-color: {{ $status['augustlock']['color'] }};">&nbsp;
                            </div>
                        </div>
                    </td>
                </tr>
                @endcan
                <tr>
                    <th>🌅 Sunset</th>
                    <td>{{$status['sunset']}}</td>
                </tr>
                <tr>
                    <th>💡 Lights on</th>
                    <td>{{$status['lightson']}}</td>
                </tr>
                <tr>
                    <th>🌌 Lights off</th>
                    <td>{{$status['homeaway']}}</td>
                </tr>
                <tr>
                    <th>🌌 Lights off</th>
                    <td>{{$status['homeaway']}}</td>
                </tr>
            </table>
            @can('can_operate_lock')
            <p><strong>Lock Control</strong></p>
            <p><a href="/gandalf/opendoor" target="_blank"><img src="/images/openhouseicon.jpg"></a>&emsp;&emsp;
               <a href="/gandalf/closedoor" target="_blank"><img src="/images/lockhouseicon.jpg"></a></p>
            @endcan
            @can('view_door_entries')
            <p><strong>Logs</strong></p>
            @if ($count['entries'] > 0)
            <p><a href="/status/door">🚪 Door Entry Log</a></p>
            @endif
            @if ($count['doorbell'] > 0)
            <p><a href="/status/doorbell">🔔 Doorbell Eents</a></p>
            @endif
            @endcan
            @can('view_boundary_entries')
            @if ($count['boundary'] > 0)
            <p><a href="/status/activity">🧭 View Home/Away Log</a></p>
            @endif
            @endcan
            @can('view_alarm_entries')
            @if ($count['alarms'] > 0)
            <p><a href="/status/sensors">🚨 Sensor Event Log</a></p>
            @endif
            <p><a target="_blank" href="http://konnected.universe.local:14984">🚨 Konnected.io Board Status</a></p>
            @endcan
            @can('view_power_status')
            <p><a href="/status/power">⚡️ View UPS Status</a></p>
            @endcan
        </div>
    </body>

    <script src="{{ asset('js/status.js') }}"></script>
</html>
