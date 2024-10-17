<?php
namespace app\models;
use app\utils\DbUtils;

class Post extends AbstractEntity {

    /** Get all the columns of the table
     * @return array
     */
    protected function getTableColumns(): array {
        return DbUtils::getTableColumns('Posts');
    }

    protected function getTableName(): string
    {
        return 'Posts';
    }
}

