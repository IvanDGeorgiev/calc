<?php

namespace app\models\expressions;

class Multiplication extends Operator {

    //Operator precedence
    protected $precedence = 2;

    /**
     * Run multiplication
     * @param Stack $stack
     * @return float|int
     */
    public function operate(Stack $stack) {
        return $stack->pop()->operate($stack) * $stack->pop()->operate($stack);
    }
}