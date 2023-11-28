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

namespace Blomstra\DatabaseQueue\Console;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Queue\Worker;

class DatabaseWorkCommand extends \Flarum\Queue\Console\WorkCommand
{
    protected $settings;

    public function __construct(Worker $worker, Cache $cache, SettingsRepositoryInterface $settings)
    {
        parent::__construct($worker, $cache);

        $this->settings = $settings;
    }

    public function handle()
    {
        $this->settings->set('database_queue.working', Carbon::now()->toIso8601String());

        try {
            parent::handle();
        } catch (\Exception $e) {
            $this->settings->delete('database_queue.working');

            throw $e;
        }
    }
}
