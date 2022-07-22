<?php

namespace app\models\expressions;

class Addition extends Operator {

    //Operator precedence
    protected $precedence = 1;

    /**
     * Run addition
     * @param Stack $stack
     * @return mixed
     */
    public function operate(Stack $stack) {
        return $stack->pop()->operate($stack) + $stack->pop()->operate($stack);
    }
}