<?php

namespace Blomstra\DatabaseQueue;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Foundation\Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Queue\DatabaseQueue as Queue;
use Illuminate\Queue\Failed\DatabaseFailedJobProvider;

class DatabaseQueue implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        $container->bind('flarum.queue.connection', function ($container) {
            $queue = new Queue(
                $container->make('db.connection'),
                'queue_jobs'
            );

            return $queue;
        });

        $container->bind('queue.failer', function ($container) {
            /** @var Config $config */
            $config = $container->make('flarum.config');

            return new DatabaseFailedJobProvider(
                $container->make('db'),
                $config->offsetGet('database.database'),
                'queue_failed_jobs'
            );
        });
    }
}
