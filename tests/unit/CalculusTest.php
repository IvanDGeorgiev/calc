<?php

use app\models\Math;

class CalculusTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @throws \yii\base\Exception
     */
    public function testCalculations()
    {
        $math = new Math();

        //Test single operations
        $this->assertTrue($math->evaluate('2+2') == 4);
        $this->assertTrue($math->evaluate('13+25') == 38);
        $this->assertTrue($math->evaluate('2*5') == 10);
        $this->assertTrue($math->evaluate('36/6') == 6);

        //Test longer operations
        $this->assertTrue($math->evaluate('2*(7+8)-1') == 29);
        $this->assertTrue($math->evaluate('100/10*2+24/6') == 24);
        $this->assertTrue($math->evaluate('2*((23-3)*3)-23*(2-3)') == 143);

        //Test parenthesis
        $this->assertTrue($math->evaluate('(15-((16/8)))-(3-(12+2))') == 24);
        $this->tester->expectException(RuntimeException::class, function() {
            $math = new Math();
            $math->evaluate('(2*(7+8)))-1');
        });
        $this->tester->expectException(RuntimeException::class, function() {
            $math = new Math();
            $math->evaluate('(2*((7+8))-1');
        });

        //Test characters
        $this->tester->expectException(Exception::class, function() {
            $math = new Math();
            $math->evaluate('(2*((7+x))-1');
        });
        $this->tester->expectException(Exception::class, function() {
            $math = new Math();
            $math->evaluate('(12+abc');
        });
    }
}