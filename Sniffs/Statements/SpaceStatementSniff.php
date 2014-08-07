<?php

class XpressEngine_Sniffs_Statements_SpaceStatementSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
						T_EQUAL, T_IS_NOT_IDENTICAL, T_IS_NOT_EQUAL, T_CONCAT_EQUAL,
						T_MUL_EQUAL, T_MINUS_EQUAL, T_PLUS_EQUAL, T_XOR_EQUAL, T_AND_EQUAL,
						T_DIVIDE, T_BITWISE_AND, T_MULTIPLY,
						T_MODULUS, T_PLUS, T_MINUS,
						T_BOOLEAN_AND, T_BOOLEAN_OR,
						T_SEMICOLON, T_COMMA,
						T_STRING_CONCAT,
						T_DOUBLE_ARROW
					);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

		$before = NULL;
		$before_permit = NULL;
		$after = NULL;

		switch($token['code'])
		{
			case T_SEMICOLON:
			case T_COMMA:
				$after = $tokens[$stackPtr + 1];
				break;

			case T_PLUS:
			case T_MINUS:
			case T_BITWISE_AND:
				$before = $tokens[$stackPtr - 1];
				$before_permit = array(T_OPEN_SQUARE_BRACKET, T_OPEN_PARENTHESIS);
				break;

			default:
				$before = $tokens[$stackPtr - 1];
				$after = $tokens[$stackPtr + 1];
		}

		if($before && $before['code'] !== T_WHITESPACE)
		{
			if(!$before_permit || !in_array($before['code'], $before_permit))
			{
				$error = "Must use space before " . substr($tokens[$stackPtr]['type'], 2);
				$phpcsFile->addError($error, $stackPtr, 'Space'); //,$tokens[$stackPtr - 1]['content'] . $tokens[$stackPtr]['content']);
			}
		}

		if($after && $after['code'] !== T_WHITESPACE)
		{
			$error = "Must use space after " . substr($tokens[$stackPtr]['type'], 2); // . " : %s";
			$phpcsFile->addError($error, $stackPtr, 'Space'); //, $tokens[$stackPtr + 1]['content'] . $tokens[$stackPtr]['content']);
		}

    }//end process()

}//end class
