<?php
class XpressEngine_Sniffs_File_DisallowPHPClosingTagSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_TAG, T_CLOSE_TAG);

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
        $content = rtrim($tokens[$stackPtr]['content']);

        $type = $tokens[$stackPtr]['type'];
		if($code === T_OPEN_TAG) 
		{
			if($content != '<?php')
			{
				$error = 'Using PHP Short Open Tag : %s';
				$phpcsFile->addError($error, $stackPtr, 'Found', $content);
			}
		}
		else if($code === T_CLOSE_TAG)
		{
			$newStartOpenTag = NULL;
			for($i=$stackPtr+1, $c=count($tokens); $i<$c; $i++)
			{
				if($tokens[$i]['code'] === T_OPEN_TAG)
				{
					$newStartOpenTag = $i;
					break;
				}
			}
			
			if(!$newStartOpenTag)
			{
				$error = 'Using PHP Close Tag : %s';
				$phpcsFile->addError($error, $stackPtr, 'Found', $content);
			}
		}

    }//end process()


}//end class
