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

use Flarum\Settings\SettingsRepositoryInterface;

class WorkerArgs
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function args(): array
    {
        $args = [
            '--stop-when-empty',
        ];

        if ($retries = $this->settings->get('blomstra-database-queue.retries')) {
            $args['--tries'] = $retries;
        }

        if ($memory = $this->settings->get('blomstra-database-queue.memory')) {
            $args['--memory'] = $memory;
        }

        if ($timeout = $this->settings->get('blomstra-database-queue.timeout')) {
            $args['--timeout'] = $timeout;
        }

        if ($rest = $this->settings->get('blomstra-database-queue.rest')) {
            $args['--rest'] = $rest;
        }

        if ($backoff = $this->settings->get('blomstra-database-queue.backoff')) {
            $args['--backoff'] = $backoff;
        }

        return $args;
    }
}
