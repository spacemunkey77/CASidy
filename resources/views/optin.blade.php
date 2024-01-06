<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oot-In for SMS Messaging</title>
    <link href="{{ mix('css/opty.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>

    <div class="container mt-4">

        <h2>Opt-In to SMS Messaging from CASidy</h2>

        @if(isset($success))
        <div id="success">
            {{$success}}
        </div>
        @endif

        <form action="{{ route('optin.save') }}" method="POST">
            @csrf
            <div class="mb-3 col-md-4">

                <div class="pt-3">
                    <div class="form-floating">
                        <input id="phone_number" name="phone_number" type="text"class="form-control">
                        <label for="phone_number">Mobile Phone Number</label>
                    </div>
                </div>

                <div class="form-check pt-3">
                    <input class="form-check-input" type="checkbox" value="1" id="optin" name="optin">
                    <label class="form-check-label" for="flexCheckDefault">
                        <strong>By clicking this checkbox and providing my mobile phone number, I am agreeing to receive SMS Text messages from CASidy. I am also aware that text messaging rates may apply from my Mobile Carrier. CASidy only uses SMS messaging to send Home Automation System alerts.</strong>
                    </label>
                </div>

                <div class="text-left pt-3">
                    <button type="submit" class="btn btn-primary">Opt-In</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>