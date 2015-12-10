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

/**
 * Class Parser
 * @package app\libraries\Data_Blocks
 */
class Parser
{

    private $error = false;
    private $error_message = "";
    private $context = -1;
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


    const NOT_IDENTIFIERS = "\" \\][(){};#+-*^&=><";
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
     * @var Token[]
     */
    private $postfix;

    /**
     * Parser constructor.
     */
    function __construct()
    {
        $this->tokenStack = array();
        $this->postfix = array();

    }

    /**
     * Parses the value of the datablock
     * @param null $value
     * @param int $context
     * @return string
     */
    function parse($value, $context = -1)
    {
        $this->context = $context;
        $this->input = $value;
        $response = "";
        $index = 0;
        $length =  strlen($value);
        while($index < $length)
        {
            $char = $value[$index];

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
             * Identifiers
             */
            if($this->peek()->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER )
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



            // standard infix operators
            if (strpos(self::OPERATORS_INFIX, substr($this->input, $index, 1)) !== false) {
                if (strlen($value) > 0) {
                    array_push(
                        $tokens1,
                        new Token($value, Token::TOKEN_TYPE_OPERAND)
                    );
                    $value = "";
                }
                array_push(
                    $tokens1,
                    new Token(substr($this->input, $index, 1), Token::TOKEN_TYPE_OPERATORINFIX)
                );
                $index++;
                continue;
            }


            /**
             * checks for parenthesis
             */





            $index++;
        }


        $index = 0;
        $length = sizeOf($this->tokenStack);
        while($index < $length)
        {
            $token = $this->tokenStack[$index];

            if($token->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER &&  $token->getTokenSubType() == Token::TOKEN_SUBTYPE_START)
            {
                $identifier = array();
                $index++; //skips the original (
                while($this->tokenStack[$index]->getTokenType() == Token::TOKEN_TYPE_IDENTIFIER && $this->tokenStack[$index]->getTokenSubType() !==  Token::TOKEN_SUBTYPE_STOP && $index < $length)
                {
                    $identifier[] = $this->tokenStack[$index];
                    $index++;
                }
                $index++; // skips the ending token
                try{
                    $processed = $this->processIdentifer($identifier);
                    $subParser = new Parser();

                    $this->postfix[] =  $subParser->parse($processed); // recursing into

                }
                catch(\Exception $e)
                {
                    return $e->getMessage();
                }

            }



        }


        $output = "";
        foreach($this->postfix as $token)
        {
            $output .= $token->getValue();
        }
        return $output;
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
        $subype = $token->getTokenSubType();
        if($type == Token::TOKEN_TYPE_OPERAND )
            return 10;
        else if($type == Token::TOKEN_TYPE_FUNCTION)
            return 9;
        else
            return -99;

    }

    /**
     * Process a group of tokens as one identifier for a datablock
     * @param Token[] $identifiers
     * @return Token
     * @throws \Exception
     */
    private function processIdentifer($identifiers)
    {
        /**
         * @var Token[][]
         */
        $tags = array();
        $tags[0] = array();
        $arrayIndex = 0;
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
        /** @var Token[] $tag */
        foreach($tags as $tag)
        {

            $first = $tag[0];
            $global = false;
            if($first->getValue() == self::SLASH)
                $global = true;
            $currentTag = "";
            $tagID = $this->context;
            /**
             * @var DataTag[] $datatags
             */
            if(isset($datatags[0]))
                $tagID = $datatags[0]->get_parent_id();

            $previousTags = [];
            $datatag = null;
            for($i = $global ? 1 : 0; $i < sizeOf($tag); $i++)
            {
                $token = $tag[$i];
                if($token->getValue() == self::SLASH)
                {
                    $datatag = DataTags::get_by_string($currentTag,$tagID);
                    if($datatag == null)
                        $datatag = DataTags::get_by_string($currentTag);
                    if($datatag == null)
                    {
                        $this->error_message = "cannot find " . $currentTag . " within the tag id " . $tagID . " or anywhere";
                        $this->error = true;
                        throw new \Exception( "cannot find " . $currentTag . " within the tag id " . $tagID . " or anywhere");
                    }
                    $tagID = $datatag->get_id();
                    $currentTag = "";
                    continue;
                }

                $currentTag .= $token->getValue();


            }
            if($currentTag != "")
            {
                $datatag = DataTags::get_by_string($currentTag,$tagID);
                $tagID = $datatag->get_id();
                $currentTag = "";
            }

            if($datatag != null)
            {
                $datatags[] = $datatag;
            }

        }

       $datablock = DataBlocks::getByTagsArray($datatags);

        return new Token($datablock->getValue(), Token::TOKEN_SUBTYPE_NUMBER, Token::TOKEN_SUBTYPE_NOTHING);
    }




}
