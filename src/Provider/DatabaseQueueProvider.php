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
use Illuminate\Queue\DatabaseQueue;
use Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider;
use Illuminate\Queue\Failed\FailedJobProviderInterface;

class DatabaseQueueProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->singleton('flarum.queue.connection', function (Container $container) {
            $queue = new DatabaseQueue(
                $container->make('db.connection'),
                'queue_jobs'
            );

            $queue->setContainer($container);

            return $queue;
        });

        $this->container->singleton('queue.failer', function (Container $container) {
            /** @var Config $config */
            $config = $container->make(Config::class);
            
            return new DatabaseUuidFailedJobProvider(
                $container->make('db'),
                $config->offsetGet('database.database'),
                'queue_failed_jobs'
            );
        });

        $this->container->alias('queue.failer', FailedJobProviderInterface::class);

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
