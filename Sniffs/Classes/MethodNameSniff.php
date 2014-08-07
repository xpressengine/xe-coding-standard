<?php

class XpressEngine_Sniffs_Classes_MethodNameSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

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

//		print_r($tokens); exit;

		// get method name
		$next = $phpcsFile->findNext(T_STRING, $stackPtr+1);
		$methodName = $tokens[$next]['content'];

		// check class name
		$conditions = $token['conditions'];
		$class = NULL;
		foreach($conditions as $key => $val)
		{
			$class = $key;
		}

		if(!$class || $tokens[$class]['code'] !== T_CLASS)
		{
			return;
		}

		$next = $phpcsFile->findNext(T_STRING, $class + 1);
		$className = $tokens[$next]['content'];

		// if constructor on PHP4
		if($className == $methodName)
		{
			return;
		}

		if(!preg_match('/^_*[a-z]/', $methodName))
		{
			$error = "Must start lowercase on method name : %s()";
			$phpcsFile->addError($error, $next, 'Method Name', $className . '::' . $methodName);
		}

    }//end process()

}//end class
