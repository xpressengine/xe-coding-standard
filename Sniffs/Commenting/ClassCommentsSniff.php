<?php
class XpressEngine_Sniffs_Commenting_ClassCommentsSniff implements PHP_CodeSniffer_Sniff
{

	/**
 	 * Returns the token types that this sniff is interested in.
	 *
 	 * @return array(int)
 	 */
	public function register()
	{
		return array(T_CLASS);
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
		$token = $tokens[$stackPtr];
		$className = $tokens[$stackPtr + 2]['content'];

//		print_r($tokens); return;

		$comments = array();

		$ptr = $phpcsFile->findPrevious(T_DOC_COMMENT, $stackPtr - 1);
		if($tokens[$ptr]['line'] + 1 !== $token['line'])
		{
			$error = 'Class must have a comment : Class %s';
			$phpcsFile->addError($error, $stackPtr, 'Found', $className);
			return;
		}

		while($tokens[$ptr]['code'] === T_DOC_COMMENT)
		{
			$comments[] = trim($tokens[$ptr--]['content']);
		}

		if(count($comments) == 0)
		{
			$error = 'Class must have a comment : Class %s';
			$phpcsFile->addError($error, $stackPtr, 'Found', $className);
			return;
		}

		$comments = array_reverse($comments);

		if($comments[0] != '/**')
		{
			$error = 'Class Comment must start "/**" : Class %s';
			$phpcsFile->addError($error, $stackPtr, 'Found', $className);
		}

		if($comments[count($comments)-1] != '*/')
		{
			$error = 'Class must must finish "*/" : Class %s';
			$phpcsFile->addError($error, $stackPtr, 'Found', $className);
		}

		$comment = join("\n", $comments);
		$commentParams = array('@author');
		foreach($commentParams as $val)
		{
			if(strpos($comment, $val) === false)
			{
				$error = 'Class Comment must have '. $val .' : Class %s';
				$phpcsFile->addError($error, $stackPtr, 'Found', $className);
			}
		}

	}//end process()

}//end class
