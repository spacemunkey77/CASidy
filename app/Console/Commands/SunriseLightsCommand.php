<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Automation\Hue\Connect;
use App\Models\AutomationSetting;
use Illuminate\Support\Facades\Log;

class SunriseLightsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:sunrise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gradually turn the light on to wake up.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);

        $light = 1; // Bedside light

        $hue = new Connect;

        $colorArray = [1 => [0.154, 0.0799], [0.154, 0.0789], [0.1539, 0.075], [0.1537, 0.067], [0.1535, 0.0595], [0.1534, 0.0568], [0.1534, 0.054], [0.1532, 0.0485], [0.1536, 0.0478], [0.1545, 0.0482], [0.1553, 0.0486], [0.1557, 0.0488], [0.1565, 0.0492], [0.1576, 0.0498], [0.1601, 0.0509], [0.1629, 0.0523], [0.1646, 0.0531], [0.1655, 0.0536], [0.1664, 0.054], [0.1677, 0.0546], [0.1706, 0.0565], [0.1729, 0.058], [0.1755, 0.0597], [0.1789, 0.0617], [0.1818, 0.0635], [0.1849, 0.0655], [0.1879, 0.0677], [0.1892, 0.0688], [0.1918, 0.0712], [0.1945, 0.0739], [0.1968, 0.0762], [0.1986, 0.078], [0.201, 0.0804], [0.203, 0.0822], [0.2043, 0.0835], [0.2061, 0.085], [0.2073, 0.0861], [0.2103, 0.0893], [0.2145, 0.0937], [0.2177, 0.0972], [0.222, 0.1019], [0.2251, 0.1059], [0.2305, 0.1143], [0.2342, 0.1204], [0.2393, 0.1282], [0.2435, 0.135], [0.2481, 0.1431], [0.2526, 0.1524], [0.2564, 0.16], [0.2598, 0.1668], [0.2651, 0.1785], [0.2692, 0.1886], [0.2723, 0.1963], [0.2751, 0.2032], [0.2777, 0.2109], [0.2799, 0.2175], [0.2828, 0.2267], [0.2854, 0.2349], [0.2873, 0.2412], [0.2908, 0.2528], [0.2937, 0.2632], [0.2956, 0.2699], [0.2965, 0.2733], [0.2971, 0.2754], [0.297, 0.2754], [0.297, 0.2758], [0.2967, 0.2791], [0.2956, 0.2793], [0.2951, 0.2799], [0.294, 0.2793], [0.2935, 0.279], [0.2927, 0.2786], [0.2916, 0.2778], [0.2907, 0.2774], [0.29, 0.2771], [0.289, 0.2762], [0.2848, 0.2737], [0.2785, 0.2703], [0.2764, 0.2696], [0.2745, 0.2699], [0.2722, 0.271], [0.2713, 0.2714], [0.2693, 0.2768], [0.2699, 0.2775], [0.2732, 0.2783], [0.2764, 0.2787], [0.2826, 0.2848], [0.2857, 0.2884], [0.2893, 0.2931], [0.291, 0.2968], [0.2903, 0.2968], [0.2859, 0.2958], [0.2832, 0.2983], [0.2822, 0.2988], [0.2815, 0.2991], [0.28, 0.2984], [0.2788, 0.2986], [0.2777, 0.2979], [0.2766, 0.2972], [0.2755, 0.2965], [0.2756, 0.2962], [0.2766, 0.296], [0.2815, 0.2966], [0.2887, 0.2955], [0.2951, 0.3012], [0.3038, 0.3085], [0.3053, 0.3107], [0.3066, 0.3127], [0.3075, 0.3143], [0.3084, 0.3158], [0.3089, 0.3163], [0.3093, 0.3165], [0.3092, 0.3136], [0.3089, 0.3094], [0.3092, 0.3051], [0.31, 0.302], [0.3101, 0.2983], [0.3106, 0.2965], [0.3109, 0.2941], [0.3113, 0.2928], [0.3113, 0.2916], [0.3113, 0.2913], [0.3116, 0.2916], [0.3119, 0.2915], [0.3135, 0.2918], [0.3153, 0.2923], [0.3156, 0.2915], [0.3157, 0.2907], [0.3158, 0.2899], [0.3159, 0.2893], [0.316, 0.2894], [0.3159, 0.289], [0.3147, 0.2874],[0.5347, 0.4068]];

        $totalColors = count($colorArray);

        $weekday = date('N');
        
        if ($weekday >= 1 && $weekday <= 5 && $json->sunrise) {

            for ($i = 1; $i <= 100; $i++) {
                $calcbri = 235 * ($i / 100);
                $i = $i + 5;
                $bri = round($calcbri, 0);
                
                $hue->set_colorxy($light, $colorArray[1], $bri);
                sleep(1);
            }

            sleep(120);

            for ($i = 2; $i <= $totalColors; $i++) {
                $hue->set_colorxy($light, $colorArray[$i], 235);
                sleep(0.5);
            }

        }

        return true;

    }
}
