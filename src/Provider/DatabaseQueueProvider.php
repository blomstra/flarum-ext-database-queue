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
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\DatabaseQueue;
use Illuminate\Queue\Failed\FailedJobProviderInterface;

class DatabaseQueueProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->extend('flarum.queue.connection', function (Queue $queue) {
            $queue = new DatabaseQueue(
                $this->container->make('db.connection'),
                'queue_jobs'
            );

            $queue->setContainer($this->container);

            return $queue;
        });

        $this->container->extend('queue.failer', function (FailedJobProviderInterface $failer) {
            /** @var Config $config */
            $config = $this->container->make(Config::class);

            return new DatabaseUuidFailedJobProvider(
                $this->container->make('db'),
                $config->offsetGet('database.database'),
                'queue_failed_jobs',
                $this->container->make('flarum.db')
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
