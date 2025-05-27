<?php

declare(strict_types=1);

namespace Aryeo\PHPStan\Rules\Tests;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * @implements Rule<Class_>
 */
class ClassMustExtendTestCaseRule implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // Ensure that the class is concrete.
        if ($node->isAbstract()) {
            return [];
        }

        // Ensure that the class is not anonymous.
        if ($node->isAnonymous()) {
            return [];
        }

        // Ensure that the class name is not null
        if ($node->name === null) {
            return [];
        }

        // Ensure that the class name is suffixed with `test`.
        if (! str_ends_with($name = $node->name->toString(), 'Test')) {
            return [];
        }

        // Ensure that the class extends another class.
        if ($node->extends === null) {
            return [];
        }

        // Ensure that the class extends `Tests\TestCase`.
        if ($node->extends->toString() === 'Tests\\TestCase') {
            return [];
        }

        return [
            RuleErrorBuilder::message("Test class [{$name}] must extend `Tests\\TestCase`.")
                ->identifier('tests.classMustExtendTestCase')
                ->build(),
        ];
    }
}
