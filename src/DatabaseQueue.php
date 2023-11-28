<?php

/*
 * This file is part of blomstra/database-queue
 *
 * Copyright (c) 2023 Blomstra Ltd.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 *
 */

namespace Blomstra\DatabaseQueue;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Foundation\Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Queue\DatabaseQueue as Queue;
use Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider;

class DatabaseQueue implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        $container->bind('flarum.queue.connection', function ($container) {
            $queue = new Queue(
                $container->make('db.connection'),
                'queue_jobs'
            );

            $queue->setContainer($container);

            return $queue;
        });

        $container->bind('queue.failer', function ($container) {
            /** @var Config $config */
            $config = $container->make('flarum.config');

            return new DatabaseUuidFailedJobProvider(
                $container->make('db'),
                $config->offsetGet('database.database'),
                'queue_failed_jobs'
            );
        });
    }
}
