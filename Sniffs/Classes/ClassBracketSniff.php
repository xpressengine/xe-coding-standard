<?php

class XpressEngine_Sniffs_Classes_ClassBracketSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLASS, T_FUNCTION);

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

		$checkPtr = $stackPtr;
		if(array_key_exists('parenthesis_closer', $token))
		{
			$checkPtr = $token['parenthesis_closer'];
		}

		if(array_key_exists('scope_opener', $token) && ($tokens[$checkPtr]['line'] + 1 !== $tokens[$token['scope_opener']]['line']))
		{
			$error = "Must use a start bracket '{' on next line";
			$phpcsFile->addError($error, $token['scope_opener'], 'Bracket');
		}

    }//end process()

}//end class
