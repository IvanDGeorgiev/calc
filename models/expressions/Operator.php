<?php

namespace app\models\expressions;

abstract class Operator extends TerminalExpression {

    //Operator precedence
    protected $precedence = 0;

    public function getPrecedence(): int {
        return $this->precedence;
    }

    public function isOperator(): bool {
        return true;
    }
}