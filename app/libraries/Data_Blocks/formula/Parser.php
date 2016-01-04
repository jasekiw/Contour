<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/8/2015
 * Time: 10:33 AM
 */

namespace app\libraries\Data_Blocks\formula;


use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;

/**
 * Class Parser
 * @package app\libraries\Data_Blocks
 */
class Parser
{

    private $error = false;
    private $error_message = "";
    private $context = -1;
    /** @var DataBlock */
    private $evaluatedDatablock = null;
    const QUOTE_DOUBLE  = '"';
    const QUOTE_SINGLE  = '\'';
    const SLASH  = '/';
    const BRACKET_CLOSE = ']';
    const BRACKET_OPEN  = '[';
    const BRACE_OPEN    = '{';
    const BRACE_CLOSE   = '}';
    const PAREN_OPEN    = '(';
    const PAREN_CLOSE   = ')';
    const SEMICOLON     = ';';
    const WHITESPACE    = ' ';
    const COMMA         = ',';
    const IDENTIFIER_START   = '#';
    const NUMBERS = '0123456789';

    const NOT_IDENTIFIERS = "\" \\][(){};#+-*^=><";
    const OPERATORS_SN 			= "+-";
    const OPERATORS_INFIX 		= "+-*/^&=><";
    const OPERATORS_POSTFIX 	= "%";
    /**
     * @var Token[]
     */

    private $tokenStack;
    /**
     * @var string
*/
    private $input;
    /**
     * @var \splStack
     */
    //private $postfix;
    /**
     * @var int
     */
    private $recursiveCheckID;

    /**
     * Parser constructor.
     */
    function __construct()
    {
        $this->tokenStack = array();
        //$this->postfix = new TokenStack();
        set_time_limit (10);

    }

    /**
     * Parses the value of the datablock
     * @param string $value
     * @param int $context the context is usually the parent of the
     * @param int|null $recursiveCheckID
     * @return string
     */
    function parse($value, $context = -1, $recursiveCheckID = null)
    {
        if(str_contains($value, "Part_A"))
        {
            $test = 5-4;
        }
        $this->recursiveCheckID = isset($recursiveCheckID) ? $recursiveCheckID : -1;
        $this->context = $context;
        $this->input = $value;
        $index = 0;
        $length =  strlen($value);
        while($index < $length)
        {
            $char = $value[$index];
            $nextChar = isset($value[$index + 1]) ? $value[$index + 1] : null;

            if($char === "7")
            {
                $test = 5-4;
            }
            if($char == " ")
            {
                $index++;
                continue;
            }
            /**
             * checks for identifier starts
             */
            if($char == self::IDENTIFIER_START)
            {
                if(($index + 1) < $length && $this->getChar($index + 1) == self::PAREN_OPEN)
                {
                    $this->addToken(new Token(self::IDENTIFIER_START . self::PAREN_OPEN, Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_START));
                    $index+= 2;
                    continue;
                }
            }

            /**
             * Check Identifiers
             */
            if($this->peek() != false && $this->peek()->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->peek()->getTokenSubType() !== Token::TOKEN_SUBTYPE_STOP)
            {
                if($this->peek()->getTokenSubType() === Token::TOKEN_SUBTYPE_IDENTIFIER_CHARACTER && $char == self::SLASH)
                {

                    $this->addToken(new Token($char , Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_IDENTIFIER_SEPERATOR));
                    $index++;
                    continue;
                }
                // if the next character is not an identifier make the ending identifer
                if($char == self::PAREN_CLOSE) //strpos(self::NOT_IDENTIFIERS, $this->getChar($index +1)) !== false
                    $this->addToken(new Token($char , Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_STOP));
                else if(strpos(self::NOT_IDENTIFIERS, $char) === false)
                    $this->addToken(new Token($char , Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_IDENTIFIER_CHARACTER));
                $index++;
                continue;
            }


            if($char == self::PAREN_OPEN)
            {
                $this->addToken(new Token($char , Token::TOKEN_TYPE_SUBEXPRESSION, Token::TOKEN_SUBTYPE_START));
                $index++;
                continue;
            }

            if($char == self::PAREN_CLOSE)
            {
                $this->addToken(new Token($char , Token::TOKEN_TYPE_SUBEXPRESSION, Token::TOKEN_SUBTYPE_STOP));
                $index++;
                continue;
            }


            if(str_contains(self::NUMBERS, $char))
            {
               $currentNumber = $char;
                while($nextChar !== null && (str_contains(self::NUMBERS, $nextChar) || '.' == $nextChar)  )
                {
                    $index++;
                    $char = $value[$index];
                    $nextChar = isset($value[$index + 1]) ? $value[$index + 1] : null;
                    $currentNumber .= $char;

                }
                $this->addToken(new Token($currentNumber , Token::TOKEN_TYPE_OPERAND, Token::TOKEN_SUBTYPE_NOTHING));
                $index++;
                continue;
            }

            if(str_contains(self::OPERATORS_INFIX, $char))
            {
                $this->addToken(new Token($char , Token::TOKEN_TYPE_OPERATORINFIX));
                $index++;
                continue;
            }
            $index++;
        }


       return $this->processTokens();
    }

    /**
     * Cycle through processed tokens and converts to postfix
     * @return string The processed Value
     */
    private function processTokens()
    {
        $postfix = new TokenStack();
        $index = 0;
        $length = sizeOf($this->tokenStack);
        /** @var Token[] $postfixExpression */
        $postfixExpression = [];
        $startOfIdentifier = -1;
        while($index < $length)
        {
            $token = $this->tokenStack[$index];

            /**
             * Proccess the identifier. Gets the value recursively
             */
            if($token->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER &&  $token->getTokenSubType() == Token::TOKEN_SUBTYPE_START) // process identifier
            {
                if($token->getTokenSubType() == Token::TOKEN_SUBTYPE_START) $startOfIdentifier = $index;
                $identifier = array();
                $index++; //skips the original (
                while($this->tokenStack[$index]->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->tokenStack[$index]->getTokenSubType() !==  Token::TOKEN_SUBTYPE_STOP && $index < $length)
                {
                    $identifier[] = $this->tokenStack[$index];
                    $index++;
                }

                $index++; // skips the ending token


                $processed = $this->processIdentifer($identifier);
                if($processed !== null)
                {
                    $subParser = new Parser();
                    $parsed = $subParser->parse($processed->getValue(),$this->evaluatedDatablock->getTags()->getRowsAsArray()[0]->get_parent_id(), $this->evaluatedDatablock->get_id() );
                    if($subParser->error)
                        return $subParser->error_message;
                    $postfixExpression[] = new Token($parsed, Token::TOKEN_TYPE_OPERAND); // recursing into
                    continue;
                }
                else
                {
//                    if( isset($this->tokenStack[$startOfIdentifier - 1]) &&  $this->tokenStack[$startOfIdentifier - 1]->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX)
//                    {
//
//                    }
                    return $this->error_message;
                }



            }


            if($token->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX)
            {
                $currentPrecedence = $this->getPrecendence($token);


                if($token->getTokenSubType() == Token::TOKEN_SUBTYPE_STOP) // if closing parenthesis, pop differently
                {
                    $parenFound = false;
                    while(!$parenFound)
                    {
                        /** @var Token $popped */
                        $popped = $postfix->pop();
                        if($popped->getTokenSubType() == Token::TOKEN_SUBTYPE_START)
                            $parenFound = true;
                        else
                            $postfixExpression[] = $popped;
                    }
                }
                else // not a parenthesis operation
                {
                    $arrayPopped = [];
                    $count = -1;
                    $greatestFound = -1;

                    while(!$postfix->isEmpty())
                    {
                        /** @var Token $popped */
                        $popped = $postfix->pop();
                        $arrayPopped[] = $popped;
                        $count++;
                        if($this->getPrecendence($popped) >=  $currentPrecedence && $popped->getTokenType() !== Token::TOKEN_TYPE_SUBEXPRESSION)
                            $greatestFound = $count;
                        else if($popped->getTokenType() == Token::TOKEN_TYPE_SUBEXPRESSION)
                            break;
                    }
                    if($greatestFound !== -1)
                    {
                        $tempStack = new TokenStack();
                        foreach($arrayPopped as $tokenIndex => $arrtoken)
                        {
                            if($tokenIndex <= $greatestFound)
                                $postfixExpression[] = $arrtoken;
                            else{
                                $tempStack->push($arrtoken);
                            }
                        }
                        while(!$tempStack->isEmpty())
                            $postfix->push($tempStack->pop());
                        $postfix->push($token);
                    }
                    else
                    {
                        $postfix->push($token);
                    }
                }
                $index++;
                continue;

            }
            if($token->getTokenType() == Token::TOKEN_TYPE_OPERAND)
            {
                $postfixExpression[] = $token;
                $index++;
                continue;
            }
            $index++;

        }
        while(!$postfix->isEmpty())
            $postfixExpression[] = $postfix->pop();
        return $this->calculatePostFix($postfixExpression);
    }

    /**
     * @param \splStack $splStack
     * @return array
     */
    private function getStackAsArray($splStack)
    {
        $arr = [];
        while(!$splStack->isEmpty())
            $arr[] = $splStack->pop();
        $index = sizeOf($arr) - 1;
        while($index >= 0)
        {
            $splStack->push($arr[$index]);
            $index--;
        }
        return $arr;
    }


    /**
     * @param Token[] $postFixExpression
     * @return string
     */
    private function calculatePostFix($postFixExpression)
    {

        $index = 0;
        $length = sizeOf($postFixExpression);
        $stack = new TokenStack();
        while($index < $length)
        {
            $token = $postFixExpression[$index];
            if($token->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX)
            {
                $answer = 0;
                $second = $stack->pop();
                $first = $stack->pop();
                $tokenValue = $token->getValue();
                if($tokenValue == '/')
                    $answer = $first / $second;
                else if($tokenValue == '*')
                    $answer = $first * $second;
                else if($tokenValue == '+')
                    $answer = $first + $second;
                else if($tokenValue == '-')
                    $answer = $first - $second;
                $stack->push($answer);
                $index++;
                continue;
            }
            if($token->getTokenType() == Token::TOKEN_TYPE_OPERAND)
            {
                $stack->push($token->getValue());
                $index++;
                continue;
            }


        }
        return $stack->pop();
    }

    /**
     * gets a character at the specified index
     * @param $index
     * @return string
     */
    private function getChar($index)
    {
        return substr($this->input, $index, 1);
    }

    /**
     * @param Token $token
     */
    private function addToken(Token $token)
    {
        array_push($this->tokenStack, $token);
    }

    /**
     * Peeks at the token stack
     * @return Token
     */
    private function peek()
    {
        return end($this->tokenStack);
    }

    /**
     * @return Token
     */
    private function pop()
    {
        return array_pop($this->tokenStack);
    }

    /**
     * @param Token $token
     * @return int
     */
    private function  getPrecendence($token)
    {
        $type = $token->getTokenType();
        $value = $token->getValue();
        //$subype = $token->getTokenSubType();
        if($type == Token::TOKEN_TYPE_OPERAND )
            return 10;
        if(str_contains("+-", $value))
            return 1;
        else if(str_contains("*/", $value))
            return 2;
        else if($type == Token::TOKEN_TYPE_SUBEXPRESSION)
            return 3;
        else if($type == Token::TOKEN_TYPE_FUNCTION)
            return 3;
        else
            return -99;

    }

    /**
     * Process a group of tokens as one identifier for a datablock
     * @param Token[] $identifiers
     * @param bool $recursived
     * @return Token
     * @throws \Exception
     */
    private function processIdentifer($identifiers, $recursived = false)
    {
        $datatags = $this->getDataTags($identifiers, $recursived);
        if($this->error)  return null;
        $datablock = DataBlocks::getByTagsArray($datatags);
        if($datablock !== null && $recursived && $datablock->get_id() == $this->recursiveCheckID)
        {
            $this->error = true;
            $this->error_message = "recursive call detected, trying to find " . $this->getTokenArrayAsString($identifiers) . " and got a recursive call";
            return null;
        }
        if($datablock === null)
        {
            $this->error = true;
            $this->error_message = "could not find " . $this->getTokenArrayAsString($identifiers) . "";
            return null;
        }
        array_push($GLOBALS['datablockIDS'], $datablock->get_id());
        $this->evaluatedDatablock = $datablock;
        return new Token($datablock->getValue(), Token::TOKEN_TYPE_OPERAND, Token::TOKEN_SUBTYPE_NOTHING);
    }

    /**
     * @param Token[] $identifiers
     * @param bool $recursived
     * @return null
     */
    private function getDataTags($identifiers, $recursived)
    {
        /**
         * @var Token[][]
         */
        $tags = array();
        $tags[0] = array();
        //$arrayIndex = 0;
        foreach($identifiers as $identifier)
        {
            if($identifier->getValue() == ",")
            {
                array_push($tags, array());
                continue;
            }
            array_push( $tags[sizeOf($tags) - 1],$identifier);
        }

        /**
         * @var DataTag[]
         */
        $datatags = [];
        $firstTag = true;
        /** @var Token[] $tag */
        foreach($tags as $tagIndex => $tag)
        {
            $first = $tag[0];
            $global = false;
            if($first->getValue() == self::SLASH)
                $global = true;
            $currentTag = "";
            $tagID = $this->context;
            $sheet = $this->getSheet($tagID);
            /** @var DataTag[] $datatags */
            if(isset($datatags[0]))
                $tagID = $datatags[0]->get_parent_id();

            $previousTags = [];
            $datatag = null;

            for($i = $global ? 1 : 0; $i < sizeOf($tag); $i++)
            {
                $token = $tag[$i];
                if($token->getValue() == self::SLASH)
                {
                    if($firstTag && $global)
                    {

                        $datatag = DataTags::get_by_string($currentTag, -1);
                    }
                    else
                    {
                        if($currentTag == "&")
                        {
                            $datatag = $sheet;
                        }
                        else
                        {
                            if($recursived && $tagIndex == 0)
                                $datatag = DataTags::get_by_id($tagID)->get_a_parent_of_type(Types::get_type_sheet())->findChild($currentTag);
                            else
                                $datatag = DataTags::get_by_string($currentTag,$tagID);
                        }

                    }

//                    if($datatag === null)
//                    {
//                        $datatag = DataTags::get_by_string($currentTag);
//                    }
                    if($datatag == null)
                    {
                        $datatag = DataTags::get_by_id($tagID)->get_a_parent_of_type(Types::get_type_sheet())->findChild($currentTag);
                    }
                    if($datatag === null)
                    {
                        $this->error_message = "cannot find " . $currentTag . " within the context " . $tagID . " or from path. trying to parse the datablockr reference " . $this->getTokenArrayAsString($identifiers);
                        $this->error = true;
                        return null;
                    }
                    $tagID = $datatag->get_id();
                    $currentTag = "";
                    $firstTag = false;
                    continue;
                }

                $currentTag .= $token->getValue();


            }
            if($currentTag != "")
            {
                if($recursived && $tagIndex == 0)
                {
                    $sheet = DataTags::get_by_id($tagID)->get_a_parent_of_type(Types::get_type_sheet());
                    $datatag = $sheet->findChild($currentTag);
                }
                else
                    $datatag = DataTags::get_by_string($currentTag,$tagID);
                if($datatag == null)
                    $datatag = $this->findTagInRespectToSheet($tagID, $currentTag);

                if($datatag === null)
                {
                    $this->error = true;
                    $this->error_message = "could not find tag " . $currentTag . " with tagID " . $tagID . " reference " . $this->getTokenArrayAsString($identifiers) . "";
                    return null;
                }

                $tagID = $datatag->get_id();
                $currentTag = "";
            }

            if($datatag !== null)
            {
                $datatags[] = $datatag;
            }

        }
        return $datatags;
    }

    /**
     * just send the id as a parameter and this function will get the sheet whether that id is the sheet or a parent is
     * @param $id
     * @return DataTag
     */
    private function getSheet($id)
    {
       $tag =  DataTags::get_by_id($id);
        if($tag == null)
            return $tag;
        if($tag->get_type()->get_id() == Types::get_type_sheet()->get_id())
            return $tag;
        return $tag->get_a_parent_of_type( Types::get_type_sheet());

    }

    /**
     * gets all of the tokens as a string
     * @param Token[] $tokenArr
     * @return string
     */
    private function getTokenArrayAsString($tokenArr)
    {
        $response = "";
        foreach($tokenArr as $token)
            $response .= $token->getValue();
        return $response;
    }
    private function findTagInRespectToSheet($tagID, $name)
    {
        $datatag = DataTags::get_by_id($tagID);
        if(!isset($datatag)) return null;
        $datatag = $datatag->get_a_parent_of_type(Types::get_type_sheet());
        if(!isset($datatag)) return null;
        return $datatag->findChild($name);

    }
}
