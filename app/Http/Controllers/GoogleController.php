<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

use Illuminate\Http\Request;
use App\Models\AutomationSetting;

class GoogleController extends Controller
{
    private $googleAuthorization;

    public function gauth(Request $request) {
        
        $this->googleAuthorization = $request->code;

        if (!empty($this->googleAuthorization) && !empty($request->scope)) {

            $this->getAccessToken();
        
        }

        $redirUrl = "https://casidy.app/status";

        return redirect($redirUrl);

    }

    private function getAccessToken() {
        $stsettings = AutomationSetting::find(5);
        $json = json_decode($stsettings->settings);

        $project_id      = $json->{"project-id"};
        $gauth_token     = $this->googleAuthorization;
        $oauth_client_id = $json->{"oauth-client-id"};
        $oauth_secret    = $json->{"oauth-secret"};
        $gauth_redir     = "https://casidy.app/gauth";

        $jar = new CookieJar();

        $accessTokenUrl = "https://www.googleapis.com/oauth2/v4/token?client_id={$oauth_client_id}.apps.googleusercontent.com&client_secret={$oauth_secret}&code={$gauth_token}&grant_type=authorization_code&redirect_uri={$gauth_redir}";

        $oauth_client = new Client([
            'cookies' => $jar,
            'headers' => [
                'content-type' => 'application/json',
            ]
        ]);

        $r = $oauth_client->request('POST', $accessTokenUrl);

        $rjson = json_decode($r->getBody());

        $json->{"refresh-token"} = $rjson->{"refresh_token"};

        $stsettings->settings = json_encode($json);
        $stsettings->save();

        return true;

    }
}
