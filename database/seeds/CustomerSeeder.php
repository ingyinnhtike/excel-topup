<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $data = [
            [
                'name' => 'BP',
                'keyword' => '664c6e50-0aed-11eb-84bc-a5983a414c54',
                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU4NDE1NmQxNTU1NDU1YmYzZWQyODVkMzE1ZWIyMzg3MDFlMzZjMTkwNTA4YzFiMThkZTIzZTQwODA2ZGJhMGRjZTUzZTlkNWEyZTdkMWNmIn0.eyJhdWQiOiIyIiwianRpIjoiNTg0MTU2ZDE1NTU0NTViZjNlZDI4NWQzMTVlYjIzODcwMWUzNmMxOTA1MDhjMWIxOGRlMjNlNDA4MDZkYmEwZGNlNTNlOWQ1YTJlN2QxY2YiLCJpYXQiOjE2MDIzMzAyMDAsIm5iZiI6MTYwMjMzMDIwMCwiZXhwIjoxNjMzODY2MjAwLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.Q34LjLYwj_3ZO6hyrXaHSEoGHEHArUod44MQQ0JFmo94e7Gy9t1ND_vwjv-eregSILdHdFl4bWH8jDnVDAELt7kdbNM1PXalAZY20k3fFk817ksbmtGwnhyiQGNflTsHcJO9KBdxTGvX5-Z13jEYmdCfXzS1K9N-Jkzw07Sb48PIK1Zf8TpmK_mUH_NCrWMC6beHd_HC-kuH6r1dpzbyrdNPxgD3uqT4tvZVSfH0l09zx4SlX10HghqKoSyIxJvcp_sCJB6LNLbT4yAO1BLWsUek_I4tcx3qODcwmyFgBqRPZU3ObZ84D8-HiT5L5IEypplEr8TzbU8961kJdNsTIXZnCq2b5jGPhuIPkqCpTvzIdSiQHog4y2Moa9XK8TOympNeziKI8-IwRcn7MNEK0M_m71c9mEQx0KxmMWHxWBLpK39LychjspASGwRlMkpFsit0CvFtwB3h3AzKRvhl82uBsnVsLCbyCV9WHmD-vl5UWla6-uYtqh8Ra_HBtgQHFRw6isT5t5IHiR6PnnlWfjCE6zUo07oDQccd6h5U6lJtGAeof7iBEUnmM9sasD34OhwmJfEgXtXPP8eyeivAAQbXp91qz2nRy6K0qEbla3heidcQeK67AeFrTkhTd-2qZaINmjSfny3_fUf6cMrOF4ayWdNB2n4g_iuIYXF37CU',
                'user_id' => 1,
                'added_user_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('customers')->insertOrIgnore($data);
    }
}
