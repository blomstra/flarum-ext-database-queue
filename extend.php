<?php

namespace Blomstra\DatabaseQueue;

use Flarum\Extend;
use Illuminate\Console\Scheduling\Event;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/resources/less/admin.less'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->get('/database-queue/stats', 'database.queue.stats', Api\Controller\ShowQueueStatsController::class),

    (new Extend\Console())
        ->command(Console\DatabaseWorkCommand::class)
        ->schedule('queue:work:db --stop-when-empty', function (Event $e) {
            $e->everyMinute();
        }),

    (new Extend\ServiceProvider())
        ->register(Provider\DatabaseQueueProvider::class),
];
