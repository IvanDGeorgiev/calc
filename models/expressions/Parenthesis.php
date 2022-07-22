<?php

namespace app\models\expressions;

class Parenthesis extends TerminalExpression {

    //Operator precedence, 3 is the highest in this context
    protected $precedence = 3;

    /**
     * @param Stack $stack
     */
    public function operate(Stack $stack) {

    }

    /**
     * @return int
     */
    public function getPrecedence(): int {
        return $this->precedence;
    }

    /**
     * @return bool
     */
    public function isParenthesis(): bool {
        return true;
    }

    /**
     * Returns wether this instance of Parenthesis is open or not
     * @return bool
     */
    public function isOpen(): bool {
        return $this->value == '(';
    }
}