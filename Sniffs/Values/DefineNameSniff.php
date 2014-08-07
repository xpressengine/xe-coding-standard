<?php
class XpressEngine_Sniffs_Values_DefineNameSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * @brief Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_STRING);
    }//end register()


    /**
     * @brief Processes the tokens that this sniff is interested in.
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
		$token = $tokens[$stackPtr];

//		print_r($tokens); exit;

		if(strtolower($token['content']) != 'define') return;

		$next = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr + 1);
		if(!$next)
		{
			return;
		}

		$next = $phpcsFile->findNext(T_CONSTANT_ENCAPSED_STRING, $next + 1, $tokens[$next]['parenthesis_closer']);
		if(!$next)
		{
			return;
		}

		$content = $tokens[$next]['content'];
		if(strtoupper($content) != $content)
		{
			$error = 'Must Use Upper Name in Define Constant Name : %s';
			$phpcsFile->addError($error, $next, 'Found', $content);
		}

    }//end process()


}//end class
