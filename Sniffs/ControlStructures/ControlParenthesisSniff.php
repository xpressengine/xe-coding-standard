<?php

class XpressEngine_Sniffs_ControlStructures_ControlParenthesisSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH);

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
//print_r($tokens); exit;
        $token = $tokens[$stackPtr];

		// check if (
		if($tokens[$stackPtr + 1]['code'] !== T_OPEN_PARENTHESIS)
		{
			$error = "Not permit spaces in Condition : %s";
			$code = $phpcsFile->getTokensAsString($stackPtr, 3);
			$phpcsFile->addError($error, $stackPtr + 1, 'Space', $code);
			return;
		}

		// check if( $a
		if($tokens[$token['parenthesis_opener'] + 1]['code'] === T_WHITESPACE
			&& strpos($tokens[$token['parenthesis_opener'] + 1]['content'], "\n") === FALSE)
		{
			$error = "Not permit spaces in Condition : %s";
			$code = $phpcsFile->getTokensAsString($stackPtr, 4);
			$phpcsFile->addError($error, $token['parenthesis_opener'] + 1, 'Space', $code);
			return;
		}

		// check if($a )
		if($tokens[$token['parenthesis_closer'] - 1]['code'] === T_WHITESPACE 
			&& strpos($tokens[$token['parenthesis_closer'] - 1]['content'], "\n") === FALSE)
		{
			$error = "Not permit spaces in Condition";
			$phpcsFile->addError($error, $token['parenthesis_closer'] - 1, 'Space');
			return;
		}

    }//end process()

}//end class
