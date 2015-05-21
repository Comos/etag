<?php
/**
 * User: wangfeng
 * Date: 15-5-17
 * Time: 上午11:49
 */
namespace Comos\Tage\Compiler;

use Comos\Tage\TageTestCase;

class ExpressionTest extends TageTestCase
{
    public function expressionProvider()
    {
        return [
            //primary
            ['123','(123)'],
            ['456.789','(456.789)'],
            ['$a','($a)'],
            ['true','(true)'],
            ['false','(false)'],
            ['null','(null)'],
            ['[]','array()'],
            ['[1,2,3]','array((1),(2),(3))'],
            ['{1,2,3}','array((1),(2),(3))'],//compatible json
            ['{1:1,2:2,3:3}','array((1)=>(1),(2)=>(2),(3)=>(3))'],//compatible json
            ['[1,2,3,]','array((1),(2),(3))'],//trailing
            ['[$x:$y,1:true]','array(($x)=>($y),(1)=>(true))'],
            ['[$x:[5,6],9:[1:1,2:2,3]]','array(($x)=>array((5),(6)),(9)=>array((1)=>(1),(2)=>(2),(3)))'],
            //unary
            ['-123','(-(123))'],
            ['+123.45','(+(123.45))'],
            ['--$a','(-(-($a)))'],
            ['not $b','(!($b))'],
            ['not not -$b','(!(!(-($b))))'],
            //binary
            ['$a+$b','(($a)+($b))'],
            ['$a-$b','(($a)-($b))'],
            ['$a*$b','(($a)*($b))'],
            ['$a/$b','(($a)/($b))'],
            ['$a%$b','(($a)%($b))'],
            ['5//4','floor((5)/(4))'],
            ['"a"~"b"','(("a").("b"))'],

            ['2..10','range((2),(10))'],
            ['$x in $arr','in_array(($x),($arr))'],
            ['$a>$b','(($a)>($b))'],
            ['$a>=$b','(($a)>=($b))'],
            ['3==5','((3)==(5))'],
            ['3!=5','((3)!=(5))'],
            ['2^3^4','pow((2),pow((3),(4)))'],

            ['$a+$b+1','((($a)+($b))+(1))'],
            ['$a+-$b+1','((($a)+(-($b)))+(1))'],
            ['3+4*5','((3)+((4)*(5)))'],
            ['(3+4)/6','(((3)+(4))/(6))'],

            //ternary
            ['true?1:0','((true)?(1):(0))'],
            ['true?$if?$yes:$no:$else?$elseYes:$elseNo','((true)?(($if)?($yes):($no)):(($else)?($elseYes):($elseNo)))'],
        ];
    }

    /**
     * @dataProvider expressionProvider
     */
    public function testExpression($exp,$compileResult)
    {
        $tpl='{{'.$exp.'}}';
        $lexer=new \Comos\Tage\Compiler\Lexer();
        $tokenStream=$lexer->lex($tpl);
        $tokenStream->next();//skip {{
        $expressionParser = new \Comos\Tage\Compiler\Parser\ExpressionParser();
        $expressionNode=$expressionParser->parse($tokenStream);
        $this->assertEquals($compileResult, $expressionNode->compile());
    }
}
