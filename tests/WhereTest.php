<?php 
use PHPUnit\Framework\TestCase;

class WhereTest extends TestCase
{
    use Fworm\Builder\Where;
    use Fworm\Properties;

    public function testWhere()
    {
        $this->where('ID', 2);

        $this->assertEquals( $this->wheres, [
            [
                'type' => 'basic',
                'column' => 'ID',
                'value' => 2,
                'operator' => '=',
                'boolean' => 'and',
            ],
        ] );

        // $where = Pruebas
        // # Caso 1
        // ::where( ['id' => 2, 'name' => 'Flikimax'] )
        // ->where( ['id' => 2, 'name' => 'Flikimax'], 'or' )
        // ->where( ['id' => 2, 'name' => 'Flikimax'], '<=' )
        // ->where( ['id' => 2, 'name' => 'Flikimax'], '<=', 'or' )

        // ->where( 'id', [1, 2, 3] )
        // ->where( 'id', [1, 2, 3], 'or' )
        // ->where( 'id', [1, 2, 3], '<=' )
        // ->where( 'id', [1, 2, 3], '<=', 'or' )

        // ->where( 'id', 2 )
        // ->where( 'id', '<=', 2, 'or' )
        
        // ->where( 'id', '2')->where('name', '=', 'Flikimax' )

        // ->get()
        // ;
    }

}