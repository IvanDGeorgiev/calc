<?php

namespace app\models;

use app\models\expressions\Operator;
use app\models\expressions\Stack;
use app\models\expressions\TerminalExpression;
use RuntimeException;
use yii\base\Exception;

class Math {

    /** Evaluates and runs the math expression
     * @param $string
     * @return string
     * @throws Exception
     */
    public function evaluate($string): string {
        $stack = $this->parse($string);
        return $this->run($stack);
    }

    /**
     * Parses the math expression into the correct order of operations
     * @param $string
     * @return Stack This is the array containing all the mathematical operations in their correct order of operation
     * @throws Exception
     */
    private function parse($string): Stack {
        $tokens = $this->tokenize($string);
        $output = new Stack();
        $operators = new Stack();
        foreach ($tokens as $token) {
            //$token = $this->extractVariables($token);
            $expression = TerminalExpression::factory($token);
            if ($expression->isOperator()) {
                $this->parseOperator($expression, $output, $operators);
            } elseif ($expression->isParenthesis()) {
                $this->parseParenthesis($expression, $output, $operators);
            } else {
                $output->push($expression);
            }
        }
        while (($op = $operators->pop())) {
            if ($op->isParenthesis()) {
                throw new RuntimeException('Mismatched Parenthesis');
            }
            $output->push($op);
        }
        return $output;
    }

    /**
     * Run the calculation
     * @param Stack $stack
     * @return string
     * @throws Exception
     */
    private function run(Stack $stack): string {
        //While there are operators left
        while (($operator = $stack->pop()) && $operator->isOperator()) {
            //Do the calculations
            $value = $operator->operate($stack);
            if (!is_null($value)) {
                $stack->push(TerminalExpression::factory($value));
            }
        }

        //return the calculated answer
        return $operator ? $operator->render() : $this->render($stack);
    }

    /**
     * Only for error handling if something goes wrong with the parsing or calculation
     * @param Stack $stack
     * @return string
     */
    protected function render(Stack $stack): string {
        $output = '';
        while (($el = $stack->pop())) {
            $output .= $el->render();
        }
        if ($output) {
            return $output;
        }
        throw new RuntimeException('Could not render output');
    }

    /**
     * Handles the parenthesis and puts operators in their correct order of operations
     * @param TerminalExpression $expression
     * @param Stack $output
     * @param Stack $operators
     */
    private function parseParenthesis(TerminalExpression $expression, Stack $output, Stack $operators) {
        //If open parenthesis
        if ($expression->isOpen()) {
            //Add the parenthesis to the operator stack
            $operators->push($expression);

        } else {
            //If Closed parenthesis
            $clean = false;
            //Add all non parenthesis operators to the output stack and remove them from the operators stack until another parenthesis met
            while (($end = $operators->pop())) {
                if ($end->isParenthesis()) {
                    //Another parenthesis has been found
                    $clean = true;
                    break;
                } else {
                    $output->push($end);
                }
            }
            //If the parenthesis count is mismatched
            if (!$clean) {
                throw new RuntimeException('Mismatched Parenthesis');
            }
        }
    }

    /**
     * Parses operators and sorts them into the output stack according to their operator precedence
     * @param Operator $operator
     * @param Stack $output
     * @param Stack $operators
     */
    private function parseOperator(Operator $operator, Stack $output, Stack $operators) {
        //Get the last operator, this can be a parenthesis
        $lastOp = $operators->poke();

        //Check if not empty and not a parenthesis
        if($lastOp && $lastOp->isOperator()) {
            do {
                //Check if the current operator has a higher operator precedence than the last operator
                if ($operator->getPrecedence() > $lastOp->getPrecedence()) {
                    break;
                }

                //Add the last operator to the output stack and remove it from the operator stack
                $output->push($operators->pop());

                //While there are operators left
            } while (($end = $operators->poke()) && $end->isOperator());
        }

        //Add the operator to the operator stack
        $operators->push($operator);
    }

    /**
     * Splits an input string into tokens with a specific conditions, removes all whitespaces and empty tokens and returns an array
     * @param $string
     * @return array
     */
    private function tokenize($string): array {
        // Split the input string into tokens with given conditions
        $parts = preg_split('((\d+|\+|-|\(|\)|\*|/)|\s+)', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        // Remove whitespaces and return array
        return array_map('trim', $parts);
    }
}