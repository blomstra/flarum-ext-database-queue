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

namespace Blomstra\DatabaseQueue\Api\Controller;

use Carbon\Carbon;
use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\DatabaseQueue;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ShowQueueStatsController implements RequestHandlerInterface
{
    /**
     * @var Queue
     */
    protected $queue;

    /**
     * @var \Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider
     */
    protected $failer;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(Queue $queue, SettingsRepositoryInterface $settings)
    {
        $this->queue = $queue;
        $this->settings = $settings;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!RequestUtil::getActor($request)->isAdmin()) {
            throw new ModelNotFoundException();
        }

        $this->failer = resolve('queue.failer');

        /** @var DatabaseQueue $queue */
        $queue = $this->queue;

        return new JsonResponse([
            'queue'       => $queue->getQueue(null),
            'status'      => $this->isStarted() ? 'running' : 'inactive',
            'pendingJobs' => $queue->size(),
            'failedJobs'  => $this->getFailedJobCount(),
        ]);
    }

    protected function getFailedJobCount(): int
    {
        return count($this->failer->all());
    }

    protected function isStarted(): bool
    {
        $dbValue = $this->settings->get('database_queue.working');

        if (!$dbValue) {
            return false;
        }

        return Carbon::parse($dbValue)->addMinutes(2)->isFuture();
    }
}
