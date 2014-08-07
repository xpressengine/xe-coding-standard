<?php

class XpressEngine_Sniffs_ControlStructures_SpaceParenthesisSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS);
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

		$checkToken = NULL;
		switch($token['code'])
		{
			case T_OPEN_PARENTHESIS:
				$checkToken = $tokens[$stackPtr + 1];
				break;
			case T_CLOSE_PARENTHESIS:
				$checkToken = $tokens[$stackPtr - 1];
				break;
		}

		if($checkToken['code'] === T_WHITESPACE && strpos($checkToken['content'], ' ') !== FALSE)
		{
			$error = "Not permit spaces in Condition";
			$phpcsFile->addError($error, $stackPtr, 'Space');
		}
    }//end process()

}//end class
