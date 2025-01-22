<?php

declare(strict_types=1);

namespace Apiera\Sniffs\Declaring;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sniffs\Declaring
 * @since 1.0.0
 */
class StrictTypesSniff implements Sniff
{
    /**
     * @return array<int, int>
     */
    public function register(): array
    {
        return [T_OPEN_TAG];
    }

    /**
     * @param File $phpcsFile
     * @param int $stackPtr
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();
        $declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr);

        if ($declarePtr === false) {
            $error = 'Missing declare(strict_types=1) declaration';
            $phpcsFile->addError($error, $stackPtr, 'Missing');
            return;
        }

        $strictTypesPtr = $phpcsFile->findNext(T_STRING, $declarePtr, null, false, 'strict_types');
        if ($strictTypesPtr === false) {
            $error = 'Missing declare(strict_types=1) declaration';
            $phpcsFile->addError($error, $declarePtr, 'Missing');
            return;
        }

        $valuePtr = $phpcsFile->findNext(T_LNUMBER, $strictTypesPtr);
        if ($valuePtr === false || $tokens[$valuePtr]['content'] !== '1') {
            $error = 'strict_types declaration must be set to 1';
            $phpcsFile->addError($error, $strictTypesPtr, 'InvalidValue');
        }
    }
}
