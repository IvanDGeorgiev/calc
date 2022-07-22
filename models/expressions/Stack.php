<?php

namespace app\models\expressions;

class Stack {

    protected $data = array();

    /**
     * Adds an element at the end of the stack
     * @param $element
     */
    public function push($element) {
        $this->data[] = $element;
    }

    /**
     * Returns the last value of the stack without removing it
     * Returns false if stack is empty
     * @return false|mixed
     */
    public function poke() {
        return end($this->data);
    }

    /**
     * Removes and returns the last value of the stack
     * Returns null if stack is empty
     * @return mixed|null
     */
    public function pop() {
        return array_pop($this->data);
    }
}