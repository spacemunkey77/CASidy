<?php

namespace App\Automation\Wemo;

use App\Models\Outlet;

class Wemo
{
    public function toggle($wemo, $switchflip = 0) {

        $wemoip = Outlet::find($wemo)->ip;

        $wemoUrl = "http://{$wemoip}:49153/upnp/control/basicevent1";

        $xml_post_string = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
          <s:Body>
            <u:GetBinaryState xmlns:u="urn:Belkin:service:basicevent:1"></u:GetBinaryState>
          </s:Body>
        </s:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            'SOAPAction: "urn:Belkin:service:basicevent:1#GetBinaryState"',
            "Content-length: ".strlen($xml_post_string),
        );

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wemoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $response = strtr($response, ['</s:' => '</', '<s:' => '<', '</u:' => '</', '<u:' => '<', ':u' => '']);

        $output = json_decode(json_encode(simplexml_load_string($response)));

        $BinaryState = $output->Body->GetBinaryStateResponse->BinaryState;

        if ($BinaryState) {
            $bState = 0;
        } else {
            $bState = 1;
        }

        switch ($switchflip) {
            case 1:
                $bState = 0;
                break;
            case 2:
                $bState = 1;
                break;
        }

        $xml_post_string = "<s:Envelope xmlns:s=\"http://schemas.xmlsoap.org/soap/envelope/\" s:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
         <s:Body>
           <u:SetBinaryState xmlns:u=\"urn:Belkin:service:basicevent:1\">
             <BinaryState>{$bState}</BinaryState>
           </u:SetBinaryState>
         </s:Body>
        </s:Envelope>";

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            'SOAPAction: "urn:Belkin:service:basicevent:1#SetBinaryState"',
            "Content-length: ".strlen($xml_post_string),
        );

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wemoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return True;
    }

    public function get_name($ip) {

        $wemoUrl = "http://{$ip}:49153/upnp/control/basicevent1";

        $xml_post_string = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
           <s:Body>
             <u:GetFriendlyName xmlns:u="urn:Belkin:service:basicevent:1"></u:GetFriendlyName>
           </s:Body>
         </s:Envelope>';

         $headers = array(
             "Content-type: text/xml;charset=\"utf-8\"",
             "Accept: text/xml",
             "Cache-Control: no-cache",
             "Pragma: no-cache",
             'SOAPAction: "urn:Belkin:service:basicevent:1#GetFriendlyName"',
             "Content-length: ".strlen($xml_post_string),
         );

         // PHP cURL  for https connection with auth
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $wemoUrl);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_TIMEOUT, 10);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $response = curl_exec($ch);
         curl_close($ch);

         $response = strtr($response, ['</s:' => '</', '<s:' => '<', '</u:' => '</', '<u:' => '<', ':u' => '']);

         $output = json_decode(json_encode(simplexml_load_string($response)));

         $FriendlyName = $output->Body->GetFriendlyNameResponse->FriendlyName;

         return $FriendlyName;
    }
}
