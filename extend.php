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

use Flarum\Extend;
use Illuminate\Console\Scheduling\Event;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\ServiceProvider())
        ->register(Provider\DatabaseQueueProvider::class),

    (new Extend\Routes('api'))
        ->get('/database-queue/stats', 'database.queue.stats', Api\Controller\ShowQueueStatsController::class),

    (new Extend\Console())
        ->command(Console\DatabaseWorkCommand::class)
        ->schedule('queue:work --stop-when-empty', function (Event $e) {
            $e->everyMinute();
        }),

    
];
