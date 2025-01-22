<?php

declare(strict_types=1);

namespace Apiera\PHPStan\Rules\DocComment;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Rule that enforces required docblock tags for classes and interfaces.
 *
 * @implements Rule<Node\Stmt>
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\PHPStan\Rules\DocComment
 * @since 0.1.0
 */
class RequiredDocCommentTagsRule implements Rule
{
    public function getNodeType(): string
    {
        // Return the common parent class of both Class_ and Interface_
        return Node\Stmt::class;
    }

    /**
     * @param Node\Stmt $node
     * @param Scope $scope
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // Skip if not a class or interface
        if (!$node instanceof Class_ && !$node instanceof Interface_) {
            return [];
        }

        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'Missing docblock comment for %s %s',
                    $node instanceof Interface_ ? 'interface' : 'class',
                    $node->name
                ))->build()
            ];
        }

        $text = $docComment->getText();
        $errors = [];
        $type = $node instanceof Interface_ ? 'interface' : 'class';
        $name = $node->name;

        if (!str_contains($text, '@author')) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Missing @author tag in docblock for %s %s', $type, $name)
            )->build();
        }

        if (!str_contains($text, '@package')) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Missing @package tag in docblock for %s %s', $type, $name)
            )->build();
        }

        if (!str_contains($text, '@since')) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Missing @since tag in docblock for %s %s', $type, $name)
            )->build();
        }

        return $errors;
    }
}
