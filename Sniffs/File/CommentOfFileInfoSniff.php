<?php
class XpressEngine_Sniffs_File_CommentOfFileInfoSniff implements PHP_CodeSniffer_Sniff
{
	static $isStart = FALSE;

    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_TAG);
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
	if(self::$isStart)
	{
		return;
	}

	self::$isStart = TRUE;

        $tokens = $phpcsFile->getTokens();
	$countTokens = count($tokens);

	$end_of_file = FALSE;
	$location = FALSE;

	$i = 1;
	while
	    (
		// traverse all comment/whitespace tokens from the end of the file
		// backwards, as long as the "End of file " and "Location:" are 
		// still to be found
		$i <= $countTokens
		    &&
		(
		    !$end_of_file
			||
		    !$location
		)
		    &&
		in_array($tokens[$countTokens-$i]['code'], array(T_COMMENT, T_WHITESPACE, T_CLOSE_TAG))
	    )
	{
	    if ($tokens[$countTokens-$i]['code'] == T_COMMENT)
	    {
		$content = trim($tokens[$countTokens-$i]['content']);

		if (strpos($content, '/* End of file ') !== FALSE)
		    $end_of_file = TRUE;

		elseif (strpos($content, '/* Location: ') !== FALSE)
		    $location = TRUE;
	    }

	    $i++;
	}

	// if($tokens[$countTokens-1]['code'] == T_WHITESPACE 
	// 		&& $tokens[$countTokens-2]['code'] == T_COMMENT 
	// 		&& $tokens[$countTokens-3]['code'] == T_WHITESPACE 
	// 		&& $tokens[$countTokens-4]['code'] == T_COMMENT)
	// {
	// 	$content = trim($tokens[$countTokens-4]['content']);
	// 	if(strpos($content, '/* End of file ') === FALSE)
	// 	{
	// 		$error = 'Comment of File Info on End of Line : %s';
	// 		$phpcsFile->addError($error, $countTokens-4, 'Found', $content);
	// 	}

	// 	$content = trim($tokens[$countTokens-2]['content']);
	// 	if(strpos($content, '/* Location: ') === FALSE)
	// 	{
	// 		$error = 'Comment of File Info on End of Line : %s';
	// 		$phpcsFile->addError($error, $countTokens-2, 'Found', $content);
	// 	}
	// }
	// else 

	if (!$end_of_file || !$location)
	{
		$content = trim($tokens[$countTokens-1]['content']);
		$error = 'Missing file info comment on Location/End of File';
		$phpcsFile->addError($error, $countTokens-1, 'Found', $content);
	}

	return;

    }//end process()

}//end class
