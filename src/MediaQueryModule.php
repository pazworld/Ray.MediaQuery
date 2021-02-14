<?php

declare(strict_types=1);

namespace Ray\MediaQuery;

use DateTimeImmutable;
use DateTimeInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Ray\MediaQuery\Annotation\DbQuery;
use Ray\MediaQuery\Annotation\SqlDir;

class MediaQueryModule extends AbstractModule
{
    /** @var string */
    private $sqlDir;

    /** @var list<class-string> */
    private $mediaQueries;

    public function __construct(string $sqlDir, Queries $mediaQueries, ?AbstractModule $module = null)
    {
        $this->mediaQueries = $mediaQueries->classes;
        $this->sqlDir = $sqlDir;
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->bind(SqlQueryInterface::class)->to(SqlQuery::class);
        $this->bind(MediaQueryLoggerInterface::class)->to(MediaQueryLogger::class)->in(Scope::SINGLETON);
        $this->bind(ParamInjectorInterface::class)->to(ParamInjector::class);
        $this->bind(DateTimeInterface::class)->to(DateTimeImmutable::class);
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(DbQuery::class),
            [MediaQueryInterceptor::class]
        );
        $this->bind()->annotatedWith(SqlDir::class)->toInstance($this->sqlDir);
        // Bind media query interface
        foreach ($this->mediaQueries as $mediaQuery) {
            $this->bind($mediaQuery)->toNull();
        }
    }
}
