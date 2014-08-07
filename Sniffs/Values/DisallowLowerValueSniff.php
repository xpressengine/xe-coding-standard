<?php
class XpressEngine_Sniffs_Values_DisallowLowerValueSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_NULL, T_FALSE, T_TRUE);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $code = $tokens[$stackPtr]['code'];
        $content = $tokens[$stackPtr]['content'];

		if(strtoupper($content) != $content)
		{
			$error = 'Must use uppercase : %s';
			$phpcsFile->addError($error, $stackPtr, 'Found', $content);
		}

    }//end process()


}//end class
