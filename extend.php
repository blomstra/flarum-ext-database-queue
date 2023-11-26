<?php

namespace Blomstra\DatabaseQueue;

use Flarum\Extend;

return [
    new DatabaseQueue(),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Routes('api'))
        ->get('/database-queue/stats', 'database.queue.stats', Api\Controller\ShowQueueStatsController::class),
];
