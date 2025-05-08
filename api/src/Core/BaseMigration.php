<?php
namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;

abstract class BaseMigration
{
    protected Builder $schema;
    protected $capsule;

    public function __construct()
    {
        $this->schema = Capsule::connection($this->getConnection())->getSchemaBuilder();
        $this->capsule = Capsule::connection($this->getConnection());
    }

    abstract public function up();

    abstract public function down();
    abstract protected function getConnection(): string;
}
