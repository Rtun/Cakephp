<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasMany('StockMovements', [
            'foreignKey' => 'product_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('name')
            ->notEmptyString('name')
            ->add('name', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'El nombre del producto debe ser único.',
            ])
            ->requirePresence('price')
            ->greaterThan('price', 0, 'El precio debe ser mayor a 0.')
            ->lessThan('price', 10000, 'El precio debe ser menor a 10000.')
            ->requirePresence('stock_quantity')
            ->integer('stock_quantity', 'Debe ser un número entero.')
            ->greaterThanOrEqual('stock_quantity', 0, 'Debe ser mayor o igual a 0.');

        return $validator;
    }
}
