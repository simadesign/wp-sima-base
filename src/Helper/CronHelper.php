<?php
namespace SimaBase\Helper;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Plugin;

class CronHelper
{

    const PER_MINUTE = 'per_minute';

    protected array $intervals = [];

    public function __construct()
    {

        add_filter('cron_schedules', [$this, '_cronSchedulesFilter']);

        $this->addInterval(self::PER_MINUTE, "One Minute", 60);

    }

    public function _cronSchedulesFilter($schedules): mixed
    {
        foreach($this->intervals as $key => $data){
            $schedules[$key] = [
                'interval' => $data['interval'],
                'display' => __($data['displayName'])
            ];
        }

        return $schedules;
    }

    public function addInterval(string $key, string $displayName, int $interval): void
    {
        $this->intervals[$key] = compact('displayName', 'interval');
    }

    public function scheduleCron($recurrence, $hook, $args = []): void
    {
        Plugin::getInstance()->onLoad(function() use($recurrence, $hook, $args) {
            if(!wp_next_scheduled($hook)){
                wp_schedule_event(time(), $recurrence, $hook, $args);
            }
        });
    }

}