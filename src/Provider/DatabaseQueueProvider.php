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

namespace Blomstra\DatabaseQueue\Provider;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Foundation\Config;
use Flarum\Queue\Console\WorkCommand;
use Illuminate\Contracts\Container\Container;
use Illuminate\Queue\DatabaseQueue as Queue;
use Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider;

class DatabaseQueueProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->bind('flarum.queue.connection', function (Container $container) {
            $queue = new Queue(
                $container->make('db.connection'),
                'queue_jobs'
            );

            /** @phpstan-ignore-next-line */
            $queue->setContainer($container);

            return $queue;
        });

        $this->container->bind('queue.failer', function (Container $container) {
            /** @var Config $config */
            $config = $container->make('flarum.config');

            return new DatabaseUuidFailedJobProvider(
                $container->make('db'),
                $config->offsetGet('database.database'),
                'queue_failed_jobs'
            );
        });

        $this->container->extend('flarum.console.commands', function (array $commands) {
            $key = array_search(WorkCommand::class, $commands);

            // If found, remove the command from the array
            if ($key !== false) {
                unset($commands[$key]);
            }

            return $commands;
        });
    }
}
