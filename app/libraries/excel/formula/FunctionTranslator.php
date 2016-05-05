<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/8/2015
 * Time: 2:03 PM
 */
namespace app\libraries\excel\Formula;

use app\libraries\excel\formula\TokenParsing\FormulaToken;

class FunctionTranslator
{
    
    private $functionList = [
        "SUM" => "sum"
    ];
    private $current_function = "";
    private $answer = "";
    private $function_contents = "";
    
    public function SUM_ADD()
    {
    }
    
    public function SUM_Stop()
    {
    }
    
    public function parse($tokens, $translations)
    {
        foreach ($tokens as $token) {
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            $this->parse_token($token, $translations);
        }
    }
    
    function parse_token($token)
    {
        if ($this->current_function == "") {
            $this->route_token($token);
        } else {
        }
    }
    
    /**
     * @param FormulaToken $token
     */
    function route_token($token)
    {
        
        if ($token->getTokenType() == "Function") {
            if ($token->getTokenSubType() == "Start") {
                $this->current_function == $token->getValue();
            } else if ($token->getTokenSubType() == "Stop") {
                $this->current_function == $token->getValue();
                $function_name = $this->current_function;
                $function_name .= "_Stop";
                $this->$function_name();
            }
        }
        
        echo $token->getTokenType();
        echo $token->getTokenSubType();
        echo "<br/>";
    }
    
} 