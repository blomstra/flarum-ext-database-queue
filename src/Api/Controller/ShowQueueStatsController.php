<?php

namespace Blomstra\DatabaseQueue\Api\Controller;

use Flarum\Http\RequestUtil;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ShowQueueStatsController implements RequestHandlerInterface
{
    /**
     * Undocumented variable
     *
     * @var \Illuminate\Queue\DatabaseQueue
     */
    protected $queue;

    /**
     * @var \Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider
     */
    protected $failer;
    
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
        $this->failer = resolve('queue.failer');
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!RequestUtil::getActor($request)->isAdmin()) {
            throw new ModelNotFoundException();
        }

        return new JsonResponse([
            'queue' => $this->queue->getConnectionName() ?? 'default',
            'status' => $this->isStarted() ? 'running' : 'inactive',
            'pendingJobs' => $this->queue->size(),
            'failedJobs' => count($this->failer->all()),
        ]);
    }

    protected function isStarted(): bool
    {
        return false;
    }
}
