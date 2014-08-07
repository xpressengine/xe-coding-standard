<?php

class XpressEngine_Sniffs_ControlStructures_ControlSignatureSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH);

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

		//print_r($tokens); exit;


		// check } else
		$checkBeforeCloseBracketLine = FALSE;

		// check ) { or else {
		$checkOpenBracketLine = FALSE;



		// check 'else if' : else if, elseif 
		$type = NULL;
		if($stackPtr > 3 && $token['code'] === T_IF && $tokens[$stackPtr - 2]['code'] === T_ELSE)
		{
			$type = 'ET_IF';
		}
		else if($token['code'] === T_ELSE && $tokens[$stackPtr + 2]['code'] === T_IF)
		{
			$type = 'ET_ELSE';
		}
		else if($token['code'] === T_ELSEIF)
		{
			$type = 'ET_ELSEIF';
		}
		else 
		{
			$type = $token['type'];
		}

		// check existing {
		if($type != 'ET_ELSE')
		{
			if(!array_key_exists('scope_opener', $token))
			{
				$error = "Not permit to omit the BRACKET '{' ";
				$phpcsFile->addError($error, $stackPtr, 'Bracket');
				return;
			}
		}


		switch($type)
		{
			case 'ET_ELSE':
				$prev = $phpcsFile->findPrevious(T_CLOSE_CURLY_BRACKET, $stackPtr - 1);
				$checkBeforeCloseBracketLine = array($token, $tokens[$prev]);

				$next = $phpcsFile->findNext(T_IF, $stackPtr + 1);
				$nextToken = $tokens[$next];

				if(!array_key_exists('scope_opener', $nextToken))
				{
					$error = "Not permit to omit the BRACKET '{' ";
					$phpcsFile->addError($error, $stackPtr, 'Bracket');
					return;
				}

				$checkBracketIndent = array($token, 
											$tokens[$nextToken['scope_opener']],
											$tokens[$nextToken['scope_closer']]);
					break;
			case 'ET_IF':
				$prev = $phpcsFile->findPrevious(T_CLOSE_CURLY_BRACKET, $stackPtr - 1);
				$checkOpenBracketLine = array($tokens[$token['parenthesis_closer']], $tokens[$token['scope_opener']]);
				$checkBeforeCloseBracketLine = array($token, $tokens[$prev]);

				$prev = $phpcsFile->findPrevious(T_ELSE, $stackPtr - 1);
				$checkBracketIndent = array($tokens[$prev],
											$token['scope_opener'],
											$token['scope_closer']);
				break;
			case 'ET_ELSEIF':
				$prev = $phpcsFile->findPrevious(T_CLOSE_CURLY_BRACKET, $stackPtr - 1);
				$checkOpenBracketLine = array($tokens[$token['parenthesis_closer']], $tokens[$token['scope_opener']]);
				$checkBeforeCloseBracketLine = array($token, $tokens[$prev]);

				$checkBracketIndent = array($token, 
											$tokens[$token['scope_opener']],
											$tokens[$token['scope_closer']]);
				break;
			case 'T_ELSE':
				$checkOpenBracketLine = array($token, $tokens[$token['scope_opener']]);

				$checkBracketIndent = array($token, 
											$tokens[$token['scope_opener']],
											$tokens[$token['scope_closer']]);
				break;
			default:
				$checkOpenBracketLine = array($tokens[$token['parenthesis_closer']], $tokens[$token['scope_opener']]);

				$checkBracketIndent = array($token, 
											$tokens[$token['scope_opener']],
											$tokens[$token['scope_closer']]);
		}


		if($checkBeforeCloseBracketLine)
		{
			if($checkBeforeCloseBracketLine[0]['line'] - 1 != $checkBeforeCloseBracketLine[1]['line'])
			{
				$error = "Not permit to use CLOSE BRACKET '}' on same line of " . $token['content'];
				$phpcsFile->addError($error, $stackPtr, 'NewLine');

				return;
			}
		}

		if($checkOpenBracketLine)
		{
			if($checkOpenBracketLine[0]['line'] + 1 != $checkOpenBracketLine[1]['line'])
			{
				$error = "Not permit to use CLOSE BRACKET '{' on same line of " . $token['content'];
				$phpcsFile->addError($error, $stackPtr, 'NewLine');
				return;
			}
		}
		
		if($checkBracketIndent)
		{
			if($checkBracketIndent[0]['column'] !== $checkBracketIndent[1]['column'])
			{
				$error = "Wrong Indent";
				$phpcsFile->addError($error, $stackPtr, 'Indent');
			}

			if($checkBracketIndent[0]['column'] !== $checkBracketIndent[2]['column'])
			{
				$error = "Wrong Indent";
				$phpcsFile->addError($error, $stackPtr, 'Indent');
			}
		}

    }//end process()

}//end class
