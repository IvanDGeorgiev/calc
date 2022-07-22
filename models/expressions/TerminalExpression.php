<?php

namespace app\models\expressions;

use yii\base\Exception;

abstract class TerminalExpression {

    protected $value = '';

    public function __construct($value) {
        $this->value = $value;
    }

    /**
     * Checks the type of given expression and creates and returns a corresponding Object
     * @param $value
     * @return Addition|Division|Multiplication|Number|Parenthesis|Subtraction|TerminalExpression
     * @throws Exception
     */
    public static function factory($value) {
        if ($value instanceof TerminalExpression) {
            return $value;
        } elseif (is_numeric($value)) {
            return new Number($value);
        } elseif ($value == '+') {
            return new Addition($value);
        } elseif ($value == '-') {
            return new Subtraction($value);
        } elseif ($value == '*') {
            return new Multiplication($value);
        } elseif ($value == '/') {
            return new Division($value);
        } elseif (in_array($value, array('(', ')'))) {
            return new Parenthesis($value);
        }
        throw new Exception('Undefined Value ' . $value);
    }

    /**
     * Run arithmetic operation
     * @param Stack $stack
     * @return mixed
     */
    abstract public function operate(Stack $stack);

    /**
     * @return bool
     */
    public function isOperator(): bool {
        return false;
    }

    /**
     * @return bool
     */
    public function isParenthesis(): bool {
        return false;
    }

    /**
     * Returns the value of the expression
     * @return string
     */
    public function render(): string {
        return $this->value;
    }
}