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

namespace Blomstra\DatabaseQueue\Tests\integration\api;

use Flarum\Testing\integration\TestCase;

class StatsTest extends TestCase
{
    public function setUp(): void
    {
        $this->extension('blomstra-database-queue');
    }

    /**
     * @test
     */
    public function non_admin_cannot_access_stats()
    {
        $response = $this->send($this->request(
            'GET',
            '/api/database-queue/stats'
        ));

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function admin_can_access_stats()
    {
        $response = $this->send($this->request(
            'GET',
            '/api/database-queue/stats',
            [
                'authenticatedAs' => 1,
            ]
        ));

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody(), true);

        $this->assertEquals('default', $body['queue']);
        $this->assertEquals('inactive', $body['status']);
        $this->assertEquals(0, $body['pendingJobs']);
        $this->assertEquals(0, $body['failedJobs']);
    }
}
