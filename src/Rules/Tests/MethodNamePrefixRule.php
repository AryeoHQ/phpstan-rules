<?php

declare(strict_types=1);

namespace Aryeo\PHPStan\Rules\Tests;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * @implements Rule<ClassMethod>
 */
class MethodNamePrefixRule implements Rule
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // Ensure that the scope is a class.
        if (! $scope->isInClass()) {
            return [];
        }

		// Ensure that the class is concrete.
		if ($scope->getClassReflection()->isAbstract()) {
			return [];
		}

		// Ensure that the method's class extends the `TestCase` class.
        $scopeReflection = $scope->getClassReflection();
        $testCaseReflection = $this->reflectionProvider->getClass('Tests\\TestCase');

        if (! $scopeReflection->isSubclassOfClass($testCaseReflection)) {
            return [];
        }

        // Ensure that the method is public.
        if (! $node->isPublic()) {
            return [];
        }

        // Ensure that the method is prefixed with `test`.
        if (str_starts_with($name = $node->name->toString(), 'test')) {
            return [];
        }

        return [
            RuleErrorBuilder::message("Test method [{$name}] must start with `test`.")
                ->identifier('tests.methodNamePrefix')
                ->build(),
        ];
    }
}
