<?php

namespace app\models\expressions;

class Number extends TerminalExpression {

    public function operate(Stack $stack) {
        return $this->value;
    }
}