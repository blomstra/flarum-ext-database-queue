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
