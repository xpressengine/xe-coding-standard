<?php

class XpressEngine_Sniffs_File_DisallowSpaceIndentSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_WHITESPACE);
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];

		if($token['column'] != 1)
		{
			return;
		}

		if(strpos($token['content'], ' ') !== false)
		{
			$error = 'Spaces are not allowed for indent';
			$phpcsFile->addError($error, $stackPtr, 'Space');
		}

	}//end process()

}//end class
