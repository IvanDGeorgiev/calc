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

        //Return the response
        echo json_encode($this->arr);
    }

    /**
     * This method checks the characters of the input requests
     * @param array $request
     * @return bool
     * true = all characters are allowed
     * false = there was an illegal character
     */
    private function checkInput(array $request): bool {
        if(!array_key_exists('query', $request)) {
            $this->arr['error'] = true;
            $this->arr['message'] = 'Invalid params';
            return false;
        }

        //Decode from Base64 to utf8
        $decoded = base64_decode($request['query']);

        // Remove all whitespaces
        $this->expression = preg_replace('/\s+/', '', $decoded);

        // Check allowed chars
        $array = str_split($this->expression);
        $valid_chars = ['+', '-', '/', '*', '(', ')'];
        foreach ($array as $char) {
            // Check numeric
            if(ctype_digit($char)) {
                continue;
            }

            // Build error response
            if(!in_array($char, $valid_chars)) {
                $this->arr['error'] = true;
                $this->arr['message'] = 'Invalid character: ' . $char ;
                return false;
            }
        }

        return true;
    }
}