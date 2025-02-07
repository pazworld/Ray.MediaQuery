<?php

declare(strict_types=1);

namespace Ray\MediaQuery\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("METHOD")
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class DbQuery
{
    /** @var string */
    public $id;

    /** @var string */
    public $entity;

    /**
     * @Enum({"row", "row_list", "exec"})
     * @var 'row'|'row_list'|'exec'
     */
    public $type = 'row_list';

    /**
     * @param 'row'|'row_list'|'exec' $type
     */
    public function __construct(string $id, string $entity = '', string $type = 'row_list')
    {
        $this->id = $id;
        $this->entity = $entity;
        $this->type = $type;
    }
}
