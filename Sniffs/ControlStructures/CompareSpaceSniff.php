<?php

class XpressEngine_Sniffs_ControlStructures_CompareSpaceSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
	{
		return array(T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL, T_LESS_THAN, T_GREATER_THAN, T_IS_SMALLER_OR_EQUAL, T_IS_GREATER_OR_EQUAL);
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

		// check $a ==$a
        if($tokens[$stackPtr - 1]['code'] !== T_WHITESPACE)
		{
			$error = 'Must Use Space Before Compare Statment : %s';
			$code = $phpcsFile->getTokensAsString($stackPtr - 1, 3);
			$phpcsFile->addError($error, $stackPtr - 1, 'Space', $code);
		}

		// check $a== $a
        if($tokens[$stackPtr + 1]['code'] !== T_WHITESPACE)
		{
			$error = 'Must Use Space After Compare Statment : %s';
			$code = $phpcsFile->getTokensAsString($stackPtr - 1, 3);
			$phpcsFile->addError($error, $stackPtr + 1, 'Space', $code);
		}

    }//end process()

}//end class
