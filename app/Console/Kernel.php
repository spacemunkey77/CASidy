<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

use App\Models\AlarmEvent;
use App\Models\Boundary;
use App\Models\Door;
use App\Models\Doorbell;
use App\Models\WeatherData;
use App\Models\Powerstatus;
use App\Models\AutomationSetting;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $asettings = AutomationSetting::find(6);
        $json = json_decode($asettings->settings);

        $sunsetfrom         = $json->sunsetfrom;
        $sunsetto           = $json->sunsetto;
        $lightsofffrom      = $json->lightsofffrom;
        $lightsoffto        = $json->lightsoffto;
        $cloudylightsfrom   = $json->cloudylightsfrom;
        $cloudylightsto     = $json->cloudylightsto;
        $lightsofftime      = $json->lightsofftime;
        $nightsafetyevening = $json->nightsafetyevening;
        $nightsafetymorning = $json->nightsafetymorning;
        $sunrisetime        = $json->sunrisetime;

        /* Every Two Minute */
        $schedule->command('casidy:power check')
          ->everyTwoMinutes();

        $schedule->command('casidy:apc')
          ->everyTwoMinutes();

        /* Every Minute Between */
        $schedule->command('casidy:sunset')
         ->between($sunsetfrom, $sunsetto)
         ->everyMinute();

        $schedule->command('casidy:lightsoff')
         ->between($lightsofffrom, $lightsoffto)
         ->everyMinute();

        /* Every Five Minutes */
        $schedule->command('casidy:weather')
         ->everyFiveMinutes();

        $schedule->command('casidy:cloudylights')
         ->between($cloudylightsfrom, $cloudylightsto)
         ->everyFiveMinutes();

        /* Every Day @ */
        $schedule->command('casidy:lightsofftime')
         ->dailyAt($lightsofftime);

        /* Every Night @ */
        $schedule->command('casidy:nightsafety')
         ->dailyAt($nightsafetyevening);

        /* Every Morning @ */
        $schedule->command('casidy:sunrise')
         ->dailyAt($sunrisetime);

        $schedule->command('casidy:nightsafety')
         ->dailyAt($nightsafetymorning);
       
        /* Every Week */
        $schedule->call(function () {
            AlarmEvent::where('created_at', '<', Carbon::now()->subDays(7))->delete();
            Boundary::where('created_at', '<', Carbon::now()->subDays(7))->delete();
            Door::where('created_at', '<', Carbon::now()->subDays(7))->delete();
            Doorbell::where('created_at', '<', Carbon::now()->subDays(7))->delete();
            Powerstatus::where('created_at', '<', Carbon::now()->subDays(7))->delete();
            
        })->weekly();

        /* Daily */
        $schedule->call(function () {
            WeatherData::truncate();
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
