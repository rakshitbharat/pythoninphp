<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class MantisPhpClientAPI
{

    public $mantis = [];
    public $MANTIS_OR_TOKEN_PASSWORD = [];

    public function connection($array)
    {
        $this->MANTIS_OR_TOKEN_PASSWORD = envCompanyTenant('MANTIS_OR_TOKEN_PASSWORD');
        if (!empty($array['MANTIS_OR_TOKEN_PASSWORD'])) {
            $this->MANTIS_OR_TOKEN_PASSWORD = $array['MANTIS_OR_TOKEN_PASSWORD'];
        }
        $headers = [
            'Authorization' => $this->MANTIS_OR_TOKEN_PASSWORD,
            'Content-Type' => 'application/json'
        ];
        $this->mantis = new \GuzzleHttp\Client();
        $this->mantis = $this->mantis->request($array['method'], envCompanyTenant('MANTIS_URL') . '/api/rest/index.php/' . $array['api_url'], [
            'headers' => $headers,
            'json' => $array['body']
        ]);
    }

    public function createIssue($array)
    {
        if (!empty($array)) {
            $array['project'] = array('name' => envCompanyTenant('MANTIS_PROJECT_NAME'));
            try {
                $this->connection([
                    'method' => 'POST',
                    'api_url' => 'issues',
                    'body' => $array,
                ]);
                return json_decode($this->mantis->getBody()->getContents(), true);
            } catch (\Exception $Exception) {
                return dd($Exception);
            }
        }
    }

    public function getMyUserInfo()
    {
        $user = [];
        if (!empty($array)) {
            $array['project'] = array('name' => envCompanyTenant('MANTIS_PROJECT_NAME'));
            try {
                $this->connection([
                    'method' => 'GET',
                    'api_url' => 'users/me',
                    'body' => [],
                ]);
                $user = json_decode($this->mantis->getBody()->getContents(), true);
                $this->MANTIS_OR_TOKEN_PASSWORD = $user;
            } catch (\Exception $Exception) {
            }
        }
        return $user;
    }

    public function createAUser()
    {
        $user = [];
        try {
            $email = Auth::guard('admin')->user()->email;
            $this->connection([
                'method' => 'POST',
                'api_url' => 'users',
                'body' => [
                    'username' => $email,
                    'password' => $email,
                    'real_name' => Auth::guard('admin')->user()->name,
                    'email' => $email,
                    'access_level' => [
                        "name" => "updater"
                    ],
                    "enabled" => true,
                    "protected" => false
                ],
            ]);
            $user = json_decode($this->mantis->getBody()->getContents(), true);
            $this->MANTIS_OR_TOKEN_PASSWORD = $user;
        } catch (\Exception $Exception) {
        }
        return $user;
    }

}