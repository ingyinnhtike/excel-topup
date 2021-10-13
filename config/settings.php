<?php

return [
    // 'URL' => env('URL', 'http://120.50.43.168:8010/PayService.svc//BuyService'),
    // 'http_call_timeout' => 30, //in seconds

    'bp_gate' => [
        'data_url' => 'https://topup.blueplanet.com.mm/api/excel/data/generate',
        'url' => 'https://topup.blueplanet.com.mm/api/top-up/generate',
        //        'token' => env('BP_GATE_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU4NDE1NmQxNTU1NDU1YmYzZWQyODVkMzE1ZWIyMzg3MDFlMzZjMTkwNTA4YzFiMThkZTIzZTQwODA2ZGJhMGRjZTUzZTlkNWEyZTdkMWNmIn0.eyJhdWQiOiIyIiwianRpIjoiNTg0MTU2ZDE1NTU0NTViZjNlZDI4NWQzMTVlYjIzODcwMWUzNmMxOTA1MDhjMWIxOGRlMjNlNDA4MDZkYmEwZGNlNTNlOWQ1YTJlN2QxY2YiLCJpYXQiOjE2MDIzMzAyMDAsIm5iZiI6MTYwMjMzMDIwMCwiZXhwIjoxNjMzODY2MjAwLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.Q34LjLYwj_3ZO6hyrXaHSEoGHEHArUod44MQQ0JFmo94e7Gy9t1ND_vwjv-eregSILdHdFl4bWH8jDnVDAELt7kdbNM1PXalAZY20k3fFk817ksbmtGwnhyiQGNflTsHcJO9KBdxTGvX5-Z13jEYmdCfXzS1K9N-Jkzw07Sb48PIK1Zf8TpmK_mUH_NCrWMC6beHd_HC-kuH6r1dpzbyrdNPxgD3uqT4tvZVSfH0l09zx4SlX10HghqKoSyIxJvcp_sCJB6LNLbT4yAO1BLWsUek_I4tcx3qODcwmyFgBqRPZU3ObZ84D8-HiT5L5IEypplEr8TzbU8961kJdNsTIXZnCq2b5jGPhuIPkqCpTvzIdSiQHog4y2Moa9XK8TOympNeziKI8-IwRcn7MNEK0M_m71c9mEQx0KxmMWHxWBLpK39LychjspASGwRlMkpFsit0CvFtwB3h3AzKRvhl82uBsnVsLCbyCV9WHmD-vl5UWla6-uYtqh8Ra_HBtgQHFRw6isT5t5IHiR6PnnlWfjCE6zUo07oDQccd6h5U6lJtGAeof7iBEUnmM9sasD34OhwmJfEgXtXPP8eyeivAAQbXp91qz2nRy6K0qEbla3heidcQeK67AeFrTkhTd-2qZaINmjSfny3_fUf6cMrOF4ayWdNB2n4g_iuIYXF37CU'),
        //        'keyword' => '664c6e50-0aed-11eb-84bc-a5983a414c54',
        'balance' => 'https://topup.blueplanet.com.mm/api/check/balance',

        // 'registeration' => 'http://128.199.134.128/api/registeration'
        'registeration' => 'https://topup.blueplanet.com.mm/api/registeration',
        'updateuserinfo' => 'https://topup.blueplanet.com.mm/api/updateuserinfo'
    ],

    'bill_top_up_feature' => env('BILL_TOPUP_FEATURE', false),
    'data_top_up_feature' => env('DATA_TOPUP_FEATURE', true),
];
