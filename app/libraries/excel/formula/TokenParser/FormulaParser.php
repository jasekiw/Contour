<?php
/*
	Copyright (c) 2007 E. W. Bachtal, Inc.
	Ported to PHP by Maarten Balliauw (http://www.balliauw.be/maarten)

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
	and associated documentation files (the "Software"), to deal in the Software without restriction, 
	including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
	and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
	subject to the following conditions:
	
	  The above copyright notice and this permission notice shall be included in all copies or substantial 
	  portions of the Software.
	
	The software is provided "as is", without warranty of any kind, express or implied, including but not 
	limited to the warranties of merchantability, fitness for a particular purpose and noninfringement. In 
	no event shall the authors or copyright holders be liable for any claim, damages or other liability, 
	whether in an action of contract, tort or otherwise, arising from, out of or in connection with the 
	software or the use or other dealings in the software. 
	
	http://ewbi.blogs.com/develops/2007/03/excel_formula_p.html
	http://ewbi.blogs.com/develops/2004/12/excel_formula_p.html
*/
namespace app\libraries\excel\formula\TokenParsing;
use Exception;

	/** FormulaToken */
//require_once('FormulaToken.php');


class FormulaParser {
	/* Character constants */
	const QUOTE_DOUBLE  = '"';
	const QUOTE_SINGLE  = '\'';
	const BRACKET_CLOSE = ']';
	const BRACKET_OPEN  = '[';
	const BRACE_OPEN    = '{';
	const BRACE_CLOSE   = '}';
	const PAREN_OPEN    = '(';
	const PAREN_CLOSE   = ')';
	const SEMICOLON     = ';';
	const WHITESPACE    = ' ';
	const COMMA         = ',';
	const ERROR_START   = '#';
	
	const OPERATORS_SN 			= "+-";
	const OPERATORS_INFIX 		= "+-*/^&=><";
	const OPERATORS_POSTFIX 	= "%";
      
	/**
	 * Formula
	 *
	 * @var string
	 */
	private $_formula;
	
	/**
	 * Tokens
	 *
	 * @var FormulaToken[]
	 */
	private $_tokens = array();
	
    /**
     * Create a new FormulaParser
     *
     * @param 	string		$pFormula	Formula to parse
     * @throws 	Exception
     */
    public function __construct($pFormula = '')
    {
    	// Check parameters
    	if (is_null($pFormula)) {
    		throw new Exception("Invalid parameter passed: formula");
    	}

    	// Initialise values
    	$this->_formula				= trim($pFormula);
    	
    	// Parse!
    	$this->_parseToTokens();
    }
	
    /**
     * Get Formula
     *
     * @return string
     */
    public function getFormula() {
    	return $this->_formula;
    }
    
    /**
     * Get Token
     *
     * @param 	int		$pId	Token id
     * @return	string
     * @throws  Exception
     */
    public function getToken($pId = 0) {
    	if (isset($this->_tokens[$pId])) {
    		return $this->_tokens[$pId];
    	} else {
    		throw new Exception("Token with id $pId does not exist.");
    	}
    }

	/**
	 * @param FormulaToken $token
	 * @param int $pId
	 * @return bool
	 * @throws Exception
     */
	public function setToken($token, $pId = 0) {
		if (isset($this->_tokens[$pId])) {
			$this->_tokens[$pId] = $token;
			return true;
		} else {
			throw new Exception("Token with id $pId does not exist.");
		}
	}
    
    /**
     * Get Token count
     *
     * @return string
     */
    public function getTokenCount() {
    	return count($this->_tokens);
    }
    
    /**
     * Get Tokens
     *
     * @return FormulaToken[]
     */
    public function getTokens() {
    	return $this->_tokens;
    }
    
    /**
     * Parse to tokens
     */
    private function _parseToTokens() {
		// No attempt is made to verify formulas; assumes formulas are derived from Excel, where 
		// they can only exist if valid; stack overflows/underflows sunk as nulls without exceptions.

		// Check if the formula has a valid starting =
		if (strlen($this->_formula) < 2 || substr($this->_formula, 0, 1) != '=') return;
		      
		// Helper variables
		$tokens1 	= array();
		$tokens2 	= array();
		$stack 		= array();
		$inString	= false;
		$inPath 	= false;
		$inRange 	= false;
		$inError 	= false;
		      
		$index	= 1;
		$value	= '';
		      
		$ERRORS 			= array("#NULL!", "#DIV/0!", "#VALUE!", "#REF!", "#NAME?", "#NUM!", "#N/A");
		$COMPARATORS_MULTI 	= array(">=", "<=", "<>");
		
		$token 			= null;
		$previousToken	= null;
		$nextToken		= null;
		
		while ($index < strlen($this->_formula))
		{
			// state-dependent character evaluation (order is important)
			        
			// double-quoted strings
			// embeds are doubled
			// end marks token
			if ($inString)
			{
				if (substr($this->_formula, $index, 1) == FormulaParser::QUOTE_DOUBLE)
				{
					// if there is another character in the string and the next character is a quote
					if ((($index + 2) <= strlen($this->_formula)) && (substr($this->_formula, $index + 1, 1) == FormulaParser::QUOTE_DOUBLE)) {
						$value .= FormulaParser::QUOTE_DOUBLE;
						$index++;
					}
					else
					{
						$inString = false;
						array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_TEXT)
						);
						$value = "";
					}      
				}
				else
				{
					$value .= substr($this->_formula, $index, 1);
				}
				$index++;
				continue;
			}
			
			// single-quoted strings (links)
			// embeds are double
			// end does not mark a token
			if ($inPath) {
				if (substr($this->_formula, $index, 1) == FormulaParser::QUOTE_SINGLE) {
					if ((($index + 2) <= strlen($this->_formula)) && (substr($this->_formula, $index + 1, 1) == FormulaParser::QUOTE_SINGLE)) {
						$value .= FormulaParser::QUOTE_SINGLE;
						$index++;
					} else {
						$inPath = false;
					}
				} else {
					$value .= substr($this->_formula, $index, 1);
				}
				$index++;
				continue; 
			}
			
			// bracked strings (R1C1 range index or linked workbook name)
			// no embeds (changed to "()" by Excel)
			// end does not mark a token
			if ($inRange) {
				if (substr($this->_formula, $index, 1) == FormulaParser::BRACKET_CLOSE) {
					$inRange = false;
				}
				$value .= substr($this->_formula, $index, 1);
				$index++;
				continue;
			}
			
			// error values
			// end marks a token, determined from absolute list of values
			if ($inError) {
				$value .= substr($this->_formula, $index, 1);
				$index++;
				if (in_array($value, $ERRORS)) {
					$inError = false;
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_ERROR)
					);
					$value = "";
				}
				continue;
			}
			
			// scientific notation check
			if (strpos(FormulaParser::OPERATORS_SN, substr($this->_formula, $index, 1)) !== false) {
				if (strlen($value) > 1) {
					if (preg_match("/^[1-9]{1}(\.[0-9]+)?E{1}$/", substr($this->_formula, $index, 1)) != 0) {
						$value .= substr($this->_formula, $index, 1);
						$index++;
						continue;
					}
				}
			}
			
			// independent character evaluation (order not important)

			// establish state-dependent character evaluations
			if (substr($this->_formula, $index, 1) == FormulaParser::QUOTE_DOUBLE) {  
				if (strlen($value > 0)) {  // unexpected
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_UNKNOWN)
					);
					$value = "";
				}
				$inString = true;
				$index++;
				continue;
 			}

			if (substr($this->_formula, $index, 1) == FormulaParser::QUOTE_SINGLE) {
				if (strlen($value) > 0) { // unexpected
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_UNKNOWN)
					);
					$value = "";
				}
				$inPath = true;
				$index++;
				continue;
			}

			if (substr($this->_formula, $index, 1) == FormulaParser::BRACKET_OPEN) {
				$inRange = true;
				$value .= FormulaParser::BRACKET_OPEN;
				$index++;
				continue;
			}

			if (substr($this->_formula, $index, 1) == FormulaParser::ERROR_START) {
				if (strlen($value) > 0) { // unexpected
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_UNKNOWN)
					);
					$value = "";
				}
				$inError = true;
				$value .= FormulaParser::ERROR_START;
				$index++;
				continue;
			}
			
			// mark start and end of arrays and array rows
			if (substr($this->_formula, $index, 1) == FormulaParser::BRACE_OPEN) {  
				if (strlen($value) > 0) { // unexpected
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_UNKNOWN)
					);
					$value = "";
				}

				$tmp = new FormulaToken("ARRAY", FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START);
				array_push($tokens1, 	$tmp);
				array_push($stack, 		clone $tmp);
				
				$tmp = new FormulaToken("ARRAYROW", FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START);
				array_push($tokens1, 	$tmp);
				array_push($stack, 		clone $tmp);

				$index++;
				continue;
			}

			if (substr($this->_formula, $index, 1) == FormulaParser::SEMICOLON) {  
				if (strlen($value) > 0) {
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}

				$tmp = array_pop($stack);
				$tmp->setValue("");
				$tmp->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_STOP);
				array_push($tokens1, 	$tmp);
				
				$tmp = new FormulaToken(",", FormulaToken::TOKEN_TYPE_ARGUMENT);
				array_push($tokens1, 	$tmp);
				
				$tmp = new FormulaToken("ARRAYROW", FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START);
				array_push($tokens1, 	$tmp);
				array_push($stack, 		clone $tmp);
				
				$index++;
				continue;
			}

			if (substr($this->_formula, $index, 1) == FormulaParser::BRACE_CLOSE) {  
				if (strlen($value) > 0) {
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				
				$tmp = array_pop($stack);
				$tmp->setValue("");
				$tmp->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_STOP);
				array_push($tokens1, 	$tmp);
				
				$tmp = array_pop($stack);
				$tmp->setValue("");
				$tmp->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_STOP);
				array_push($tokens1, 	$tmp);
				
				$index++;
				continue;
			}
			
			// trim white-space
			if (substr($this->_formula, $index, 1) == FormulaParser::WHITESPACE) {
				if (strlen($value) > 0) {
					array_push(
						$tokens1,
						new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				array_push(
					$tokens1,
					new FormulaToken("", FormulaToken::TOKEN_TYPE_WHITESPACE)
				);
				$index++;
				while ((substr($this->_formula, $index, 1) == FormulaParser::WHITESPACE) && ($index < strlen($this->_formula))) {
					$index++;
				}
				continue;
			}
			
			// multi-character comparators
			if (($index + 2) <= strlen($this->_formula)) {
				if (in_array(substr($this->_formula, $index, 2), $COMPARATORS_MULTI)) {
					if (strlen($value) > 0) {
						array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
						);
						$value = "";
					}
					array_push(
						$tokens1,
						new FormulaToken(substr($this->_formula, $index, 2), FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_LOGICAL)
					);
					$index += 2;
					continue;     
				}
			}
			
			// standard infix operators
			if (strpos(FormulaParser::OPERATORS_INFIX, substr($this->_formula, $index, 1)) !== false) {
				if (strlen($value) > 0) {
					array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				array_push(
						$tokens1,
						new FormulaToken(substr($this->_formula, $index, 1), FormulaToken::TOKEN_TYPE_OPERATORINFIX)
				);
				$index++;
				continue;     
			}
			
			// standard postfix operators (only one)
			if (strpos(FormulaParser::OPERATORS_POSTFIX, substr($this->_formula, $index, 1)) !== false) {
				if (strlen($value) > 0) {
					array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				array_push(
						$tokens1,
						new FormulaToken(substr($this->_formula, $index, 1), FormulaToken::TOKEN_TYPE_OPERATORPOSTFIX)
				);
				$index++;
				continue;     
			}

			// start subexpression or function 
			if (substr($this->_formula, $index, 1) == FormulaParser::PAREN_OPEN) {
				if (strlen($value) > 0) {
					$tmp = new FormulaToken($value, FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START);
					array_push($tokens1, 	$tmp);
					array_push($stack, 		clone $tmp);
					$value = "";
				} else {
					$tmp = new FormulaToken("", FormulaToken::TOKEN_TYPE_SUBEXPRESSION, FormulaToken::TOKEN_SUBTYPE_START);
					array_push($tokens1, 	$tmp);
					array_push($stack, 		clone $tmp);
				}
				$index++;
				continue;
			}
        
			// function, subexpression, or array parameters, or operand unions
			if (substr($this->_formula, $index, 1) == FormulaParser::COMMA) {
				if (strlen($value) > 0) {
					array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				
				$tmp = array_pop($stack);
				$tmp->setValue("");
				$tmp->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_STOP);
				array_push($stack, $tmp);
				
				if ($tmp->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) {
					array_push(
							$tokens1,
							new FormulaToken(",", FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_UNION)
					);
				} else {
					array_push(
							$tokens1,
							new FormulaToken(",", FormulaToken::TOKEN_TYPE_ARGUMENT)
					);
				}
				$index++;
				continue;
			}

			// stop subexpression
			if (substr($this->_formula, $index, 1) == FormulaParser::PAREN_CLOSE) {
				if (strlen($value) > 0) {
					array_push(
							$tokens1,
							new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
					);
					$value = "";
				}
				
				$tmp = array_pop($stack);
				$tmp->setValue("");
				$tmp->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_STOP);
				array_push($tokens1, $tmp);
				
				$index++;
				continue;
			}

        	// token accumulation
			$value .= substr($this->_formula, $index, 1);
			$index++;
		}


		/**
		 * We are done with infix to postfix conversion
		 */

		/**
		 * Starting postfix covnersion
		 */

		
		// dump remaining accumulation
		if (strlen($value) > 0) {
			array_push(
					$tokens1,
					new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERAND)
			);
		}
		
		// move tokenList to new set, excluding unnecessary white-space tokens and converting necessary ones to intersections
		for ($i = 0; $i < count($tokens1); $i++) {
			$token = $tokens1[$i];
			if (isset($tokens1[$i - 1])) {
				$previousToken = $tokens1[$i - 1];
			} else {
				$previousToken = null;
			}
			if (isset($tokens1[$i + 1])) {
				$nextToken = $tokens1[$i + 1];
			} else {
				$nextToken = null;
			}
			
			if (is_null($token)) {
				continue;
			}

			/** @var FormulaToken $token */
			if ($token->getTokenType() != FormulaToken::TOKEN_TYPE_WHITESPACE) {
				array_push($tokens2, $token);
				continue;        
			}
			
			if (is_null($previousToken)) {
				continue;
			}

			/** @var FormulaToken $previousToken */
			if (! (
					(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
					(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
					($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERAND)
				  ) ) {
				continue;
			}
			
			if (is_null($nextToken)) {
				continue;
			}

			/** @var FormulaToken $nextToken */
			if (! (
					(($nextToken->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) && ($nextToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START)) ||
					(($nextToken->getTokenType() == FormulaToken::TOKEN_TYPE_SUBEXPRESSION) && ($nextToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_START)) ||
					($nextToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERAND)
				  ) ) {
				continue;
			}
			
			array_push(
				$tokens2,
				new FormulaToken($value, FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_INTERSECTION)
			);
		}
		
		// move tokens to final list, switching infix "-" operators to prefix when appropriate, switching infix "+" operators 
		// to noop when appropriate, identifying operand and infix-operator subtypes, and pulling "@" from function names
		$this->_tokens = array();
		
		for ($i = 0; $i < count($tokens2); $i++) {
			/** @var FormulaToken $token */
			$token = $tokens2[$i];
			if (isset($tokens2[$i - 1])) {
				/** @var FormulaToken $previousToken */
				$previousToken = $tokens2[$i - 1];
			} else {
				$previousToken = null;
			}
			if (isset($tokens2[$i + 1])) {
				$nextToken = $tokens2[$i + 1];
			} else {
				$nextToken = null;
			}
			
			if (is_null($token)) {
				continue;
			}
			
			if ($token->getTokenType() == FormulaToken::TOKEN_TYPE_OPERATORINFIX && $token->getValue() == "-") {
				if ($i == 0) {
					$token->setTokenType(FormulaToken::TOKEN_TYPE_OPERATORPREFIX);
				} else if (
							(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
							(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
							($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERATORPOSTFIX) ||
							($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERAND)
						) {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_MATH);
				} else {
					$token->setTokenType(FormulaToken::TOKEN_TYPE_OPERATORPREFIX);
				}
				
				array_push($this->_tokens, $token);
				continue;
			} 

			if ($token->getTokenType() == FormulaToken::TOKEN_TYPE_OPERATORINFIX && $token->getValue() == "+") {
				if ($i == 0) {
					continue;
				} else if (
							(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
							(($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_SUBEXPRESSION) && ($previousToken->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_STOP)) ||
							($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERATORPOSTFIX) ||
							($previousToken->getTokenType() == FormulaToken::TOKEN_TYPE_OPERAND)
						) {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_MATH);
				} else {
					continue;
				}
				
				array_push($this->_tokens, $token);
				continue;
			}
			
			if ($token->getTokenType() == FormulaToken::TOKEN_TYPE_OPERATORINFIX && $token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_NOTHING) {
				if (strpos("<>=", substr($token->getValue(), 0, 1)) !== false) {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_LOGICAL);
				} else if ($token->getValue() == "&") {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_CONCATENATION);
				} else {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_MATH);
				}
	
				array_push($this->_tokens, $token);
				continue;
			}
			
			if ($token->getTokenType() == FormulaToken::TOKEN_TYPE_OPERAND && $token->getTokenSubType() == FormulaToken::TOKEN_SUBTYPE_NOTHING) {
				if (!is_numeric($token->getValue())) {
					if (strtoupper($token->getValue()) == "TRUE" || strtoupper($token->getValue() == "FALSE")) {
						$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_LOGICAL);
					} else {
						$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_RANGE);
					}			
				} else {
					$token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_NUMBER);
				}
				
				array_push($this->_tokens, $token);
				continue;
			}
			
			if ($token->getTokenType() == FormulaToken::TOKEN_TYPE_FUNCTION) {
				if (strlen($token->getValue() > 0)) {
					if (substr($token->getValue(), 0, 1) == "@") {
						$token->setValue(substr($token->getValue(), 1));
					}
				}
			}
        
        	array_push($this->_tokens, $token);
		}
    }
}
