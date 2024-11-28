<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class StockMovementsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('stock_movements');
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
        ]);
    }
}