<?php

namespace app\controllers;

use app\models\Math;
use Yii;
use yii\web\Controller;

class CalculusController extends Controller
{
    public $arr = [];
    public $expression;

    public function actionIndex() {
        $req = Yii::$app->request->queryParams;
        if($this->checkInput($req)) {
            $math = new Math();

            $this->arr['error'] = false;
            $this->arr['result'] = $math->evaluate($this->expression);
        }

        echo json_encode($this->arr);
    }

    private function checkInput(array $request) {
        if(!array_key_exists('query', $request)) {
            $this->arr['error'] = true;
            $this->arr['message'] = 'Invalid params';
            return false;
        }

        // Remove all whitespace
        $this->expression = preg_replace('/\s+/', '', $request['query']);

        // Check allowed chars
        $array = str_split($this->expression);
        $valid_chars = ['+', '-', '/', '*', '(', ')'];
        foreach ($array as $char) {
            // Check numeric
            if(ctype_digit($char)) {
                continue;
            }

            if(!in_array($char, $valid_chars)) {
                $this->arr['error'] = true;
                $this->arr['message'] = 'Invalid character: ' . $char ;
                return false;
            }
        }

        return true;
    }
}