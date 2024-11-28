<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StockMovementsFixture
 */
class StockMovementsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'product_id' => 1,
                'quantity_changed' => 1,
                'reason' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-11-26 06:25:12',
            ],
        ];
        parent::init();
    }
}
