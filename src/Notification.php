<?php

namespace AbuseIO\Notification;

use ReflectionClass;
use Log;

class Notification
{
    /**
     * Configuration Basename (parser name)
     * @var string
     */
    public $configBase;

    /**
     * Create a new Notification instance
     */
    public function __construct($notification)
    {
        $this->startup($notification);
    }

    /**
     * Generalize the local config based on the parser class object.
     * @return void
     */
    protected function startup($notification)
    {
        $reflect = new ReflectionClass($notification);

        $this->configBase = 'notifications.' . $reflect->getShortName();

        if (empty(config("{$this->configBase}.notification.name"))) {
            $this->failed("Required notification.name is missing in notification configuration");
        }

        Log::info(
            '(JOB ' . getmypid() . ') ' . get_class($this) . ': ' .
            'A notification module has been called: ' .
            config("{$this->configBase}.notification.name")
        );

    }

    /**
     * Return failed
     * @param  string $message
     * @return array
     */
    protected function failed($message)
    {
        $this->cleanup();

        return [
            'errorStatus'   => true,
            'errorMessage'  => $message,
        ];
    }

    /**
     * Return success
     * @return array
     */
    protected function success()
    {
        $this->cleanup();

        return [
            'errorStatus'   => false,
            'errorMessage'  => 'Notification sucessfully send',
        ];
    }

    /**
     * Cleanup anything a parser might have left (basically, remove the working dir)
     * @return void
     */
    protected function cleanup()
    {

    }
}
