<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 12/8/2015
 * Time: 10:33 AM
 */

namespace app\libraries\datablocks\formula;

use app\libraries\contour\Contour;
use app\libraries\database\DataManager;
use app\libraries\datablocks\DataBlock;
use app\libraries\datablocks\DataBlockCollection;
use app\libraries\helpers\StringStack;
use app\libraries\helpers\TimeTracker;
use app\libraries\memory\MemoryDataManager;
use app\libraries\tags\DataTag;
use app\libraries\types\Types;

/**
 * Class Parser
 * @package app\libraries\datablocks
 */
class Parser
{
    
    const QUOTE_DOUBLE = '"';
    const QUOTE_SINGLE = '\'';
    const SLASH = '/';
    const BRACKET_CLOSE = ']';
    const BRACKET_OPEN = '[';
    const BRACE_OPEN = '{';
    const BRACE_CLOSE = '}';
    const PAREN_OPEN = '(';
    const PAREN_CLOSE = ')';
    const SEMICOLON = ';';
    const WHITESPACE = ' ';
    const COMMA = ',';
    const IDENTIFIER_START = '#';
    const NUMBERS = '0123456789';
    const FUNCTION_TYPE_SUM = "SUM_FUNCTION";
    const NOT_IDENTIFIERS = "\" \\][(){};#+-*^=><";
    const OPERATORS_SN = "+-";
    const OPERATORS_INFIX = "+-*/^&=><";
    const OPERATORS_POSTFIX = "%";
    const ERROR_TYPE_WARNING = 'WARNING';
    const ERROR_TYPE_FATAL = 'FATAL';
    const IGNORE_WARNINGS = "INGORE_WARNINGS";
    private $error = false;
    private $error_type = null;
    private $error_message = "";
    private $context = -1;
    /** @var DataBlock */
    private $evaluatedDatablock = null;
    /** @var Token[]   */
    
    private $tokenStack;
    private $debug = false;
    /** @var string   */
    private $input;
    /** @var \splStack   */
    //private $postfix;
    /** @var int   */
    private $recursiveCheckID;
    /** @var TokenStack   */
    private $functionStack = null;
    
    /** @var DataManager | MemoryDataManager   */
    private $manager;

    /**
     * Parser constructor.
     * @param DataManager $manager
     */
    function __construct($manager)
    {
        $this->manager = $manager;
        $this->tokenStack = [];
        $this->functionStack = new StringStack();
        Contour::getConfigManager()->setTimeLimit(10);
    }
    
    /**
     * Parses the value of the datablock
     *
     * @param string   $value
     * @param int      $context the context is usually the parent of the
     * @param int|null $recursiveCheckID
     *
     * @return string
     */
    function parse($value, $context = -1, $recursiveCheckID = null)
    {
//        if($value == "#(Operating_Income, Jan)/#(Revenue/Total_Revenues, Jan)")
//            $this->debug = true;
        $timer = null;
        
        $this->recursiveCheckID = isset($recursiveCheckID) ? $recursiveCheckID : -1; // recursive check id is used to see if any of the parsed datablocks referr to itself.
        $this->context = $context; // the context to be searching within
        $this->input = $value;
        $index = 0;
        $length = strlen($value);
        while ($index < $length) {
            $char = $value[$index];
            $nextChar = isset($value[$index + 1]) ? $value[$index + 1] : null;
            
            if ($char == " ") {
                $index++;
                continue;
            }
            if (strtoupper($this->getNCharacters($index, 4)) == "SUM(") {
                $index += 4;
                $tokenToAdd = new Token(self::FUNCTION_TYPE_SUM, Token::TOKEN_TYPE_FUNCTION, Token::TOKEN_SUBTYPE_START);
                $this->addToken($tokenToAdd);
                $this->functionStack->push(self::FUNCTION_TYPE_SUM);
                continue;
            }
            if ($char == ":") {
                $this->addToken(new Token($char, Token::TOKEN_TYPE_FUNCTION, Token::TOKEN_SUBTYPE_RANGE));
                $index++;
                continue;
            }
            
            /**
             * checks for identifier starts
             */
            if ($char == self::IDENTIFIER_START) {
                if (($index + 1) < $length && $this->getChar($index + 1) == self::PAREN_OPEN) {
                    $this->addToken(new Token(self::IDENTIFIER_START . self::PAREN_OPEN, Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_START));
                    $index += 2;
                    continue;
                }
            }
            
            /**
             * Check Identifiers
             */
            if ($this->peek() != false && $this->peek()->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->peek()->getTokenSubType() !== Token::TOKEN_SUBTYPE_STOP) {
                if ($this->peek()->getTokenSubType() === Token::TOKEN_SUBTYPE_IDENTIFIER_CHARACTER && $char == self::SLASH) {
                    
                    $this->addToken(new Token($char, Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_IDENTIFIER_SEPERATOR));
                    $index++;
                    continue;
                }
                // if the next character is not an identifier make the ending identifer
                if ($char == self::PAREN_CLOSE) //strpos(self::NOT_IDENTIFIERS, $this->getChar($index +1)) !== false
                    $this->addToken(new Token($char, Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_STOP));
                else if (strpos(self::NOT_IDENTIFIERS, $char) === false)
                    $this->addToken(new Token($char, Token::TOKEN_TYPE_IDENTIFIER, Token::TOKEN_SUBTYPE_IDENTIFIER_CHARACTER));
                $index++;
                continue;
            }
            
            if ($char == self::PAREN_OPEN) {
                $this->addToken(new Token($char, Token::TOKEN_TYPE_SUBEXPRESSION, Token::TOKEN_SUBTYPE_START));
                $index++;
                continue;
            }
            
            if ($char == self::PAREN_CLOSE && $this->functionStack->top() === null) {
                $this->addToken(new Token($char, Token::TOKEN_TYPE_SUBEXPRESSION, Token::TOKEN_SUBTYPE_STOP));
                $index++;
                continue;
            } else if ($char == self::PAREN_CLOSE) // function is in the stack
            {
                $this->addToken(new Token($this->functionStack->pop(), Token::TOKEN_TYPE_FUNCTION, Token::TOKEN_SUBTYPE_STOP));
                $index++;
                continue;
            }
            
            if (str_contains(self::NUMBERS, $char)) {
                $currentNumber = $char;
                while ($nextChar !== null && (str_contains(self::NUMBERS, $nextChar) || '.' == $nextChar)) {
                    $index++;
                    $char = $value[$index];
                    $nextChar = isset($value[$index + 1]) ? $value[$index + 1] : null;
                    $currentNumber .= $char;
                }
                $this->addToken(new Token($currentNumber, Token::TOKEN_TYPE_OPERAND, Token::TOKEN_SUBTYPE_NOTHING));
                $index++;
                continue;
            }
            
            if (str_contains(self::OPERATORS_INFIX, $char)) {
                $this->addToken(new Token($char, Token::TOKEN_TYPE_OPERATORINFIX));
                $index++;
                continue;
            }
            $index++;
        }
        //$this->preprocessSUM();
        $this->preprocessIdentifiers();
        if ($this->error && $this->error_type == self::ERROR_TYPE_FATAL)
            return $this->error_message;
        if ($this->debug)
            exit;
        return $this->processTokens();
    }
    
    /**
     * Gets a substring of the whole input safetly. out of bounds allowed
     *
     * @param int $index The index to start the substring
     * @param int $n     The length of the substring
     *
     * @return string The substring
     */
    private function getNCharacters($index, $n)
    {
        $stringToReturn = "";
        for ($i = $index; $i < ($n + $index); $i++) {
            $char = isset($this->input[$i]) ? $this->input[$i] : null;
            if ($char == " ")
                continue;
            if (isset($char))
                $stringToReturn .= $char;
        }
        return $stringToReturn;
    }
    
    /**
     * Adds a token to the tokenStack
     *
     * @param Token $token
     */
    private function addToken(Token $token)
    {
        array_push($this->tokenStack, $token);
    }
    
    /**
     * gets a character at the specified index
     *
     * @param $index
     *
     * @return string
     */
    private function getChar($index)
    {
        return substr($this->input, $index, 1);
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
     * This method converts the identifiers to actual numbers
     */
    private function preprocessIdentifiers()
    {
        
        $infixExpression = []; //the infix expression to insert tokens into after the datablocks have been converted
        $index = 0; // the starting index to cycle through the tokens with
        $length = sizeof($this->tokenStack); // the size of the token stack
        while ($index < $length) {
            $token = $this->tokenStack[$index];
            /**
             * Proccess the identifier. Gets the value recursively
             */
            if ($token->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $token->getTokenSubType() == Token::TOKEN_SUBTYPE_START) // process identifier
            {
                $identifier = []; // store current tokens into an array for processing by the processIdentifer method
                $index++; //skips the original (
                while ($this->tokenStack[$index]->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->tokenStack[$index]->getTokenSubType() !== Token::TOKEN_SUBTYPE_STOP && $index < $length) {
                    $identifier[] = $this->tokenStack[$index];
                    $index++;
                }
                $index++; // skips the ending token
                $processed = $this->processIdentifer($identifier);
                if ($processed !== null) {
//                    $subParser = new Parser($this->manager);
//                    $parsed = $subParser->parse($processed->getValue(), $this->evaluatedDatablock->getTags()->getTagWithTypeAsArray(Types::getTagPrimary())[0]->get_parent_id(), $this->evaluatedDatablock->get_id());
//                    if ($subParser->error) {
//                        $this->error = $subParser->error;
//                        $this->error_type = $subParser->error_type;
//                        $this->error_message = $subParser->error_message;
//                        return;
//                    }
                //from parsed
                    $infixExpression[] = new Token($processed->getValue(), Token::TOKEN_TYPE_OPERAND); // recursing into
                    continue;
                } else if ($this->error_type == self::ERROR_TYPE_WARNING) {
                    /** @var Token $endingToken */
                    $endingToken = end($infixExpression);
                    if ($endingToken !== false && $endingToken->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX) {
                        array_pop($infixExpression);
                        continue;
                    } else if (isset($this->tokenStack[$index]) && $this->tokenStack[$index]->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX) {
                        $index++;
                        continue;
                    } else
                        continue;
                } else
                    return;
            }
            
            $infixExpression[] = $token;
            $index++;
        }
        $this->tokenStack = $infixExpression;
        return;
    }
    
    /**
     * Process a group of tokens as one identifier for a datablock
     *
     * @param Token[] $identifiers
     * @param bool    $recursived
     *
     * @return Token
     * @throws \Exception
     */
    private function processIdentifer($identifiers, $recursived = false)
    {
        $timer = null;
        if ($this->debug) {
            $timer = new TimeTracker();
            $timer->startTimer("processIdentifer");
        }
        $datatags = $this->getDataTags($identifiers, $recursived);
        
        if ($this->error) return null;
//        $datablock = $this->manager->dataBlockManager->getByTagsArray($datatags);
        $datablocks = $this->manager->dataBlockManager->getMultipleByTagsArray($datatags);
        $evaluation = 0;
        foreach($datablocks->getAsArray(DataBlockCollection::SORT_TYPE_NONE) as $datablock)
        {
            /**
             * @var DataBlock $datablock
             */
            if ($datablock !== null && $recursived && $datablock->get_id() == $this->recursiveCheckID) {
                $this->error = true;
                $this->error_type = self::ERROR_TYPE_FATAL;
                $this->error_message = "recursive call detected, trying to find " . $this->getTokenArrayAsString($identifiers) . " and got a recursive call";
                return null;
            }

            if($this->manager  instanceof MemoryDataManager)
                $currentValue = $datablock->getProccessedValue(true);
            else
                $currentValue = $datablock->getProccessedValue(false);

            if(!is_numeric($currentValue))
            {
               $evaluation = strval($evaluation);
               $evaluation .=  $currentValue;
            }
            else if(is_numeric($evaluation))
                $evaluation += floatval($currentValue);
            else
                $evaluation .= $currentValue;
        }
//        if ($datablock !== null && $recursived && $datablock->get_id() == $this->recursiveCheckID) {
//            $this->error = true;
//            $this->error_type = self::ERROR_TYPE_FATAL;
//            $this->error_message = "recursive call detected, trying to find " . $this->getTokenArrayAsString($identifiers) . " and got a recursive call";
//            return null;
//        }
//        if ($datablock === null) {
//            $this->error = true;
//            $this->error_type = self::ERROR_TYPE_WARNING;
//            $this->error_message = "could not find " . $this->getTokenArrayAsString($identifiers) . "";
//            return null;
//        }
        if($datablocks->getSize() == 0)
        {
            $this->error = true;
            $this->error_type = self::ERROR_TYPE_WARNING;
            $this->error_message = "could not find " . $this->getTokenArrayAsString($identifiers) . "";
            return null;

        }
        else
            $this->evaluatedDatablock = $datablocks->getAsArray(DataBlockCollection::SORT_TYPE_NONE)[0];
        if ($this->debug) {
            $timer->stopTimer("processIdentifer");
            $timer->getResults();
        }
        return new Token($evaluation, Token::TOKEN_TYPE_OPERAND, Token::TOKEN_SUBTYPE_NOTHING);
    }
    
    /**
     * Gets the tags from the identifiers
     *
     * @param Token[] $identifiers
     * @param bool    $recursived
     *
     * @return null
     */
    private function getDataTags($identifiers, $recursived = false)
    {
        
        /** @var Token[][] */
        $tags = [];
        $tags[0] = [];
        //$arrayIndex = 0;
        foreach ($identifiers as $identifier) {
            if ($identifier->getValue() == ",") {
                array_push($tags, []);
                continue;
            }
            array_push($tags[sizeof($tags) - 1], $identifier);
        }
        
        /** @var DataTag[] */
        $datatags = [];
        $firstTag = true;
        /** @var Token[] $tag */
        foreach ($tags as $tagIndex => $tag) {
            $first = $tag[0];
            $global = false;
            if ($first->getValue() == self::SLASH)
                $global = true;
            $currentTag = "";
            $tagID = $this->context;
            $sheet = $this->getSheet($tagID);
            /** @var DataTag[] $datatags */
            if (isset($datatags[0]))
                $tagID = $datatags[0]->get_parent_id();
            
            $previousTags = [];
            $datatag = null;
            
            for ($i = $global ? 1 : 0; $i < sizeof($tag); $i++) {
                $token = $tag[$i];
                if ($token->getValue() == self::SLASH) // there are multiple tags
                {
                    if ($firstTag && $global)
                        $datatag = $this->manager->dataTagManager->get_by_string($currentTag, -1);
                    else {
                        if ($currentTag == "&")
                            $datatag = $sheet;
                        else {
                            if ($recursived && $tagIndex == 0)
                                $datatag = $this->manager->dataTagManager->get_by_id($tagID)->get_parent_of_type(Types::get_type_sheet())->findChild($currentTag);
                            else
                                $datatag = $this->manager->dataTagManager->get_by_string($currentTag, $tagID);
                        }
                    }

                    if ($datatag == null)
                        $datatag = $this->manager->dataTagManager->get_by_id($tagID)->get_parent_of_type(Types::get_type_sheet())->findChild($currentTag);
                    
                    if ($datatag === null) {
                        $this->error_message = "cannot find " . $currentTag . " within the context " . $tagID . " or from path. trying to parse the datablockr reference " . $this->getTokenArrayAsString($identifiers);
                        $this->error_type = self::ERROR_TYPE_FATAL;
                        $this->error = true;
                        return null;
                    }
                    $tagID = $datatag->get_id();
                    $currentTag = "";
                    $firstTag = false;
                    continue;
                }
                
                $currentTag .= $token->getValue();
            } // end loop through characters
            
            if ($currentTag != "") // is there any characters left?
            {
                if ($recursived && $tagIndex == 0) {
                    $sheet = $this->manager->dataTagManager->get_by_id($tagID)->get_parent_of_type(Types::get_type_sheet());
                    $datatag = $sheet->findChild($currentTag);
                } else
                    $datatag = $this->manager->dataTagManager->get_by_string($currentTag, $tagID);
                
                if ($datatag == null)
                    $datatag = $this->findTagInRespectToSheet($tagID, $currentTag);
                
                if ($datatag === null) {
                    $this->error = true;
                    $this->error_type = self::ERROR_TYPE_FATAL;
                    $this->error_message = "could not find tag " . $currentTag . " with tagID " . $tagID . " reference " . $this->getTokenArrayAsString($identifiers) . "";
                    return null;
                }
                
                $tagID = $datatag->get_id();
                $currentTag = "";
            }
            
            if ($datatag !== null)
                $datatags[] = $datatag;
        }
        
        return $datatags;
    }
    
    /**
     * just send the id as a parameter and this function will get the sheet whether that id is the sheet or a parent is
     *
     * @param $id
     *
     * @return DataTag
     */
    private function getSheet($id)
    {
        $tag = $this->manager->dataTagManager->get_by_id($id);
        if ($tag == null)
            return $tag;
        if ($tag->get_type()->get_id() == Types::get_type_sheet()->get_id())
            return $tag;
        return $tag->get_parent_of_type(Types::get_type_sheet());
    }
    
    /**
     * gets all of the tokens as a string
     *
     * @param Token[] $tokenArr
     *
     * @return string
     */
    private function getTokenArrayAsString($tokenArr)
    {
        $response = "";
        foreach ($tokenArr as $token)
            $response .= $token->getValue();
        return $response;
    }
    
    /**
     * Gets the current tag name in respect to the sheet of the tagID instead of in respect to the tagID itself
     *
     * @param int    $tagID
     * @param string $name
     *
     * @return DataTag|null
     */
    private function findTagInRespectToSheet($tagID, $name)
    {
        $datatag = $this->manager->dataTagManager->get_by_id($tagID);
        if (!isset($datatag)) return null;
        $datatag = $datatag->get_parent_of_type(Types::get_type_sheet());
        if (!isset($datatag)) return null;
        return $datatag->findChild($name);
    }
    
    /**
     * Cycle through processed tokens and converts to postfix
     * @return string The processed Value
     */
    private function processTokens()
    {
        $postfix = new TokenStack();
        $index = 0;
        $length = sizeof($this->tokenStack);
        /** @var Token[] $postfixExpression */
        $postfixExpression = [];
        $startOfIdentifier = -1;
        while ($index < $length) {
            $token = $this->tokenStack[$index];
            
            if ($token->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX) {
                $currentPrecedence = $this->getPrecendence($token);
                if ($token->getTokenSubType() == Token::TOKEN_SUBTYPE_STOP) // if closing parenthesis, pop differently
                {
                    $parenFound = false;
                    while (!$parenFound) {
                        /** @var Token $popped */
                        $popped = $postfix->pop();
                        if ($popped->getTokenSubType() == Token::TOKEN_SUBTYPE_START)
                            $parenFound = true;
                        else
                            $postfixExpression[] = $popped;
                    }
                } else // not a parenthesis operation
                {
                    $arrayPopped = [];
                    $count = -1;
                    $greatestFound = -1;
                    
                    while (!$postfix->isEmpty()) {
                        /** @var Token $popped */
                        $popped = $postfix->pop();
                        $arrayPopped[] = $popped;
                        $count++;
                        if ($this->getPrecendence($popped) >= $currentPrecedence && $popped->getTokenType() !== Token::TOKEN_TYPE_SUBEXPRESSION)
                            $greatestFound = $count;
                        else if ($popped->getTokenType() == Token::TOKEN_TYPE_SUBEXPRESSION)
                            break;
                    }
                    if ($greatestFound !== -1) {
                        $tempStack = new TokenStack();
                        foreach ($arrayPopped as $tokenIndex => $arrtoken) {
                            if ($tokenIndex <= $greatestFound)
                                $postfixExpression[] = $arrtoken;
                            else {
                                $tempStack->push($arrtoken);
                            }
                        }
                        while (!$tempStack->isEmpty())
                            $postfix->push($tempStack->pop());
                        $postfix->push($token);
                    } else {
                        $postfix->push($token);
                    }
                }
                $index++;
                continue;
            }
            if ($token->getTokenType() == Token::TOKEN_TYPE_OPERAND) {
                $postfixExpression[] = $token;
                $index++;
                continue;
            }
            $index++;
        }
        while (!$postfix->isEmpty())
            $postfixExpression[] = $postfix->pop();
        return $this->calculatePostFix($postfixExpression);
    }
    
    /**
     * @param Token $token
     *
     * @return int
     */
    private function getPrecendence($token)
    {
        $type = $token->getTokenType();
        $value = $token->getValue();
        //$subype = $token->getTokenSubType();
        if ($type == Token::TOKEN_TYPE_OPERAND)
            return 10;
        if (str_contains("+-", $value))
            return 1;
        else if (str_contains("*/", $value))
            return 2;
        else if ($type == Token::TOKEN_TYPE_SUBEXPRESSION)
            return 3;
        else if ($type == Token::TOKEN_TYPE_FUNCTION)
            return 3;
        else
            return -99;
    }
    
    /**
     * Runs mathematical calculation on the postfix expression
     *
     * @param Token[] $postFixExpression
     *
     * @return string
     */
    private function calculatePostFix($postFixExpression)
    {
        
        $index = 0;
        $length = sizeof($postFixExpression);
        $stack = new TokenStack();
        while ($index < $length) {
            $token = $postFixExpression[$index];
            if ($token->getTokenType() == Token::TOKEN_TYPE_OPERATORINFIX) {
                $answer = 0;
                $second = $stack->pop();
                $first = $stack->pop();
                $tokenValue = $token->getValue();
                if ($tokenValue == '/') {
                    $answer = $first / $second;
                } else if ($tokenValue == '*')
                    $answer = $first * $second;
                else if ($tokenValue == '+')
                    $answer = $first + $second;
                else if ($tokenValue == '-')
                    $answer = $first - $second;
                $stack->push($answer);
                $index++;
                continue;
            }
            if ($token->getTokenType() == Token::TOKEN_TYPE_OPERAND) {
                $stack->push($token->getValue());
                $index++;
                continue;
            }
        }
        return $stack->pop();
    }
    
    /**
     * Checks the string for the sum function
     */
    private function preprocessSUM()
    {
        $infixExpression = []; //the infix expression to insert tokens into after the datablocks have been converted
        $index = 0; // the starting index to cycle through the tokens with
        $length = sizeof($this->tokenStack); // the size of the token stack
        $startingTags = null;
        $endingTags = null;
        while ($index < $length) {
            $token = $this->tokenStack[$index];
            /**
             * Proccess the identifier. Gets the value recursively
             */
            if ($token->getValue() == self::FUNCTION_TYPE_SUM && $token->getTokenType() == Token::TOKEN_TYPE_FUNCTION && $token->getTokenSubType() == Token::TOKEN_SUBTYPE_START) // process identifier
            {
                $identifier = []; // store current tokens into an array for processing by the processIdentifer method
                $index += 2; //skips the original ( and starting #(
                while ($this->tokenStack[$index]->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->tokenStack[$index]->getTokenSubType() !== Token::TOKEN_SUBTYPE_STOP && $index < $length) {
                    $identifier[] = $this->tokenStack[$index];
                    $index++;
                }
                $index++; // skips the ending token
                $startingTags = $this->getDataTags($identifier);
            }
            
            $infixExpression[] = $token;
            $index++;
        }
        $this->tokenStack = $infixExpression;
        return;
    }
}
