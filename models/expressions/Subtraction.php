<?php

namespace app\models\expressions;

class Subtraction extends Operator {

    //Operator precedence
    protected $precedence = 1;

    /**
     * Run subtraction
     * @param Stack $stack
     * @return mixed
     */
    public function operate(Stack $stack) {
        $right = $stack->pop()->operate($stack);
        $left = $stack->pop()->operate($stack);
        return $left - $right;
    }
}