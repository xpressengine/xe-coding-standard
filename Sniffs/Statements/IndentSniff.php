<?php

class XpressEngine_Sniffs_Statements_IndentSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_CURLY_BRACKET);
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
	$indent = "\t";
	//print_r($tokens); exit;

	$startPtr = $stackPtr;
	$endPtr = $tokens[$stackPtr]['bracket_closer'];
	$startLine = $tokens[$startPtr]['line'];
	$endLine = $tokens[$endPtr]['line'];
	$checkLevel = $tokens[$stackPtr]['level'] + 1;
	$lineIndent = str_repeat($indent, $checkLevel);
	$caseIndent = str_repeat($indent, $checkLevel - 1);

	$codes = array();
	$ptrs = array();
	$here_document = FALSE;

	for($i = $startPtr + 1; $i < $endPtr; $i++)
	{
	    $token = $tokens[$i];
	    $line = $token['line'];

	    if ($here_document)
	    {
		if ($token['code'] == T_END_HEREDOC)
		    $here_document = FALSE;

		continue;
	    }

	    if ($token['code'] == T_START_HEREDOC)
		$here_document = TRUE;

	    if($line == $startLine || $line == $endLine)
	    {
		continue;
	    }
	    
	    if(!array_key_exists($line, $codes))
	    {
		$codes[$line] = '';
		$ptrs[$line] = $i;
	    }

	    $codes[$line] .= $token['content'];
	}

	foreach($codes as $line => $code)
	{
	    if(trim($code) != '')	// non-empty line
		if (preg_match('#^[[:blank:]]*((case[^[:alnum:]].*)|(default[[:blank:]]*)):#', $code))
		    if ($caseIndent && strpos($code, $caseIndent) !== 0)
		    {
			$error = 'Wrong Indent';
			$phpcsFile->addError($error, $ptrs[$line], 'Indent');
		    }
		    else
			;
		else
		    if (strpos($code, $lineIndent) !== 0)
		    {
			$error = 'Wrong Indent';
			$phpcsFile->addError($error, $ptrs[$line], 'Indent');
		    }
	}

    }//end process()

}//end class
