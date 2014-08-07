<?php

class XpressEngine_Sniffs_Classes_ClassConstructorSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_NEW);
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

		if($tokens[$stackPtr + 3]['code'] !== T_OPEN_PARENTHESIS)
		{
			$error = "Not permit to omit the parenthesis '()' on class constuctor call";
			$phpcsFile->addError($error, $stackPtr + 3, 'Class');
		}

    }//end process()

}//end class
