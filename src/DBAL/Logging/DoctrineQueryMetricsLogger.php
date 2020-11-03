<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DBAL\Logging;

use Doctrine\DBAL\Logging\SQLLogger;
use Neto\OpenmetricsBundle\Metrics\MetricsCollectorInterface;

/**
 * Class DoctrineQueryMetricsLogger
 *
 * Collects metrics from doctrine using the SQLLogger "hooks".
 * We keep the total number of queries and time spent executing queries and an inferred connection count
 * for the purpose of seeing average queries per request and query time per request.
 *
 * @package Neto\OpenmetricsBundle\DBAL\Logging
 */
class DoctrineQueryMetricsLogger implements SQLLogger
{
    /** @var MetricsCollectorInterface */
    private $collector;

    /** @var float */
    private $startTime;

    /** @var float */
    private $totalTime = 0.0;

    /** @var int */
    private $totalQueries = 0;

    public function __construct(MetricsCollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function __destruct()
    {
        if ($this->totalQueries > 0) {
            $this->collector->inc(
                'database_connections',
                'Count of database connections'
            );

            $this->collector->incBy(
                $this->totalQueries,
                'database_queries',
                'Count of database queries'
            );

            $this->collector->incBy(
                (int) round($this->totalTime * 1000),
                'database_query_time',
                'Sum of time (ms) spent executing database queries'
            );
        }

        $this->collector = null;
    }

    /**
     * @inheritDoc
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        $this->startTime = microtime(true);
    }

    /**
     * @inheritDoc
     */
    public function stopQuery()
    {
        if ($this->startTime === null) {
            return;
        }

        $queryTime = microtime(true) - $this->startTime;
        $this->startTime = null;

        $this->totalQueries++;
        $this->totalTime += $queryTime;
    }
}
