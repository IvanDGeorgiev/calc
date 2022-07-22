<?php

namespace app\models\expressions;

class Number extends TerminalExpression {

    /**
     * Numbers just return their value
     * @param Stack $stack
     * @return mixed|string
     */
    public function operate(Stack $stack) {
        return $this->value;
    }
}