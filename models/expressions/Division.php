<?php

namespace app\models\expressions;

class Division extends Operator {

    //Operator precedence
    protected $precedence = 2;

    /**
     * Run division
     * @param Stack $stack
     * @return float|int
     */
    public function operate(Stack $stack) {
        $left = $stack->pop()->operate($stack);
        $right = $stack->pop()->operate($stack);
        return $right / $left;
    }
}