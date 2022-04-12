<?php 
use PHPUnit\Framework\TestCase;

class WhereTest extends TestCase
{
    use Fworm\Builder\Where;
    use Fworm\Properties;

    public function testWhere()
    {
        # Caso 1
        $case1 = $this->where(['id' => 1, 'name' => 'Flikimax'])
            ->where( ['id' => 1, 'name' => 'Flikimax'], 'or' )
            ->where( ['id' => 1, 'name' => 'Flikimax'], '<=' )
            ->where( ['id' => 1, 'name' => 'Flikimax'], '<=', 'or' );

        $this->assertIsObject( $case1 );
        $this->assertIsArray( $case1->wheres );

        $this->assertEquals( $case1->wheres, [
            // where(['id' => 1, 'name' => 'Flikimax'])
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'name',
                'value' => 'Flikimax',
                'operator' => '=',
                'boolean' => 'and',
            ],
            // where( ['id' => 1, 'name' => 'Flikimax'], 'or' )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'name',
                'value' => 'Flikimax',
                'operator' => '=',
                'boolean' => 'or',
            ],
            // where( ['id' => 1, 'name' => 'Flikimax'], '<=' )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '<=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'name',
                'value' => 'Flikimax',
                'operator' => '<=',
                'boolean' => 'and',
            ],
            // where( ['id' => 1, 'name' => 'Flikimax'], '<=', 'or' );
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '<=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'name',
                'value' => 'Flikimax',
                'operator' => '<=',
                'boolean' => 'or',
            ]
        ] );
        $this->wheres = [];

        # Caso 2
        $case2 = $this->where( 'id', [1, 2, 3] )
            ->where( 'id', [1, 2, 3], 'or' )
            ->where( 'id', [1, 2, 3], '<=' )
            ->where( 'id', [1, 2, 3], '<=', 'or' );

        $this->assertIsObject( $case2 );
        $this->assertIsArray( $case2->wheres );

        $this->assertEquals( $case2->wheres, [
            // where( 'id', [1, 2, 3] )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 2,
                'operator' => '=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 3,
                'operator' => '=',
                'boolean' => 'and',
            ],
            // where( 'id', [1, 2, 3], 'or' )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 2,
                'operator' => '=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 3,
                'operator' => '=',
                'boolean' => 'or',
            ],
            // where( 'id', [1, 2, 3], '<=' )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '<=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 2,
                'operator' => '<=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 3,
                'operator' => '<=',
                'boolean' => 'and',
            ],
            // where( 'id', [1, 2, 3], '<=', 'or' )
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '<=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 2,
                'operator' => '<=',
                'boolean' => 'or',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 3,
                'operator' => '<=',
                'boolean' => 'or',
            ]
        ] );
        $this->wheres = [];

        # Caso 3
        $case3 = $this->where( 'id', 1 );

        $this->assertIsObject( $case3 );
        $this->assertIsArray( $case3->wheres );

        $this->assertEquals( $case3->wheres, [
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'and',
            ]
        ] );
        $this->wheres = [];
        
        # Caso 4 (default):
        $case4 = $this->where( 'id', '=', 1 )
            ->where( 'id', '=', 1, 'or' );

        $this->assertIsObject( $case4 );
        $this->assertIsArray( $case4->wheres );

        $this->assertEquals( $case4->wheres, [
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'and',
            ],
            [
                'type' => 'basic',
                'column' => 'id',
                'value' => 1,
                'operator' => '=',
                'boolean' => 'or',
            ],
        ] );
    }

}