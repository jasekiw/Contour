<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/8/2015
 * Time: 11:33 AM
 */
namespace app\libraries\excel\Formula;
use app\libraries\excel\formula\TokenParsing\FormulaParser;
use app\libraries\Math\EvalMath;
use Exception;

class FormulaCalculator {

    //formula will have tags
    /**
     * @param $formula
     */
    private $formula = "";
    private $translations = array();
    private $regex_expression = '(\[>.+?<\])';
    public function __construct($formula)
    {
        if(gettype($formula) != "string")
        {
            throw new Exception("Invalid parameter passed: formula. Input must be a string.");
        }
        $this->formula = $formula;
    }
    public function calculate()
    {
        if($this->has_whole_tag())
        {
            $translations_and_formula = $this->convert_formula_to_virtual_cells($this->formula);
            $this->translations = $translations_and_formula["translations"];
            $this->process_formula($translations_and_formula["formula"], $translations_and_formula["translations"] );

        }
        else{

            $this->process_formula();
        }

    }

    /**
     * @param string $formula with translations
     * @param null|array $translations
     */
    function process_formula($formula = null, $translations = null)
    {
        if(!isset($formula))
        {
            $formula = $this->formula;
        }

        $parser = new FormulaParser($formula);
        $tokens = $parser->getTokens();

//        $translator = new FunctionTranslator();
//        $translator->parse($tokens, $translations);
        $evaluator = new  EvalMath();
        


    }


    public function convert_tags_to_ids($formula)
    {

    }
    public function convert_operands_to_ids($formula)
    {

    }

    function convert_formula_to_virtual_cells($formula)
    {
        $virtualCellTranslation = array();
        $count = 1;
        $offset = 0;
        while ( preg_match($this->regex_expression, $formula, $m, PREG_OFFSET_CAPTURE, $offset))
        {
            $replacement = "A" . $count;
            $offset = $m[0][1] + strlen($replacement);
            $selected = $m[0][0];
            $start_int = $m[0][1];
            $formula = substr_replace($formula, "A" . $count, $start_int, strlen($selected));
            $virtualCellTranslation["A" . $count] =  array(
                "selected" => $selected,
                "after_replace" => $formula,
                "indices" => array(
                    "start" => $offset,
                    "length" => strlen($selected)
                )
            );
            $count++;
        }
        return array(
            "formula" => $formula,
            "translations" => $virtualCellTranslation
        );
    }

    function has_whole_tag()
    {
        if(preg_match($this->regex_expression, $this->formula))
            return true;
        return false;
    }

    function get_expression_results($expression, $str)
    {

    }
} 