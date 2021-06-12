<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


trait WhereProtection
{
    /**
     * @var bool
     */
    private bool $where_protection = true;

    public function disableWhereProtection() {
        $this->where_protection = false;
    }
}
