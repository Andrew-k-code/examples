<?php

namespace AndrewKode\SapIntegration\Plugin\Cron;

use Magento\Cron\Model\Schedule;
use AndrewKode\SapIntegration\Model\Config;

class SchedulePlugin
{
    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Plugin to override cron schedule from admin config
     *
     * @param Schedule $subject
     * @param callable $proceed
     * @param string $cronExpression
     * @return bool
     */
    public function aroundMatchCronExpression(
        Schedule $subject,
        callable $proceed,
        string $cronExpression
    ): bool {
        // Only override for SAP Integration cron job
        if ($subject->getJobCode() === 'sapintegration_sync_orders') {
            $configSchedule = $this->config->getSyncSchedule();
            if ($configSchedule && $configSchedule !== '*/5 * * * *') {
                $cronExpression = $configSchedule;
            }
        }

        return $proceed($cronExpression);
    }
}
