<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Config;
use Illuminate\Support\Facades\DB;

class PopcornMailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // if (\Schema::hasTable('mails')) {
        //     $mail = DB::table('mails')->first();
        //     if ($mail) //checking if table is not empty
        //     {
        //         $config = array(
        //             'driver'     => $mail->driver,
        //             'host'       => $mail->host,
        //             'port'       => $mail->port,
        //             'from'       => array('address' => $mail->username, 'name' => $mail->name),
        //             'encryption' => $mail->encryption,
        //             'username'   => $mail->username,
        //             'password'   => $mail->password,
        //             'sendmail'   => '/usr/sbin/sendmail -bs',
        //             'pretend'    => false,
        //         );
        //         Config::set('mail', $config);
        //     }
        // }

    }
}
