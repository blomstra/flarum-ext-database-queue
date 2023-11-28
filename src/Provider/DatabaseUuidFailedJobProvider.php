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

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;

class DatabaseUuidFailedJobProvider extends \Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider
{
    protected $connection;

    public function __construct(ConnectionResolverInterface $resolver, $database, $table, ConnectionInterface $connection)
    {
        parent::__construct($resolver, $database, $table);

        $this->connection = $connection;
    }

    /**
     * Get a new query builder instance for the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable()
    {
        return $this->connection->table($this->table);
    }
}
