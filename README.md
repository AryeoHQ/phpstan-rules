# Aryeo's PHPStan Rules

## How to Install & Use in Your Project

Follow these steps to integrate this custom‐rule pack into any PHP project running PHPStan:

1. **Require via Composer** (adjust package name as needed):

   ```bash
   composer require --dev aryeo/phpstan-rules
   ```

2. **Add (or merge) `rules.neon` into your application’s PHPStan config**:

    * In your project root, you probably have a `phpstan.neon` or `phpstan.neon.dist`.
    * Ensure it includes this package’s `rules.neon`. For example:

      ```neon
      # phpstan.neon.dist

      includes:
          - vendor/aryeo/phpstan-rules/rules.neon

      parameters:
          # 1) Enable/disable test rules:
          aryeo:
              tests:
                  enabled: true
                  # Override TestCase class if needed
                  testCaseClass: App\Tests\TestCase

          # 2) Your other PHPStan settings:
          level: max
          paths:
              - src
              - tests
      ```
    * The `includes:` line loads all sections from the package’s `rules.neon` (including `conditionalTags`, `parameters`, `parametersSchema`, and `services`).

3. **Override parameters (optional)**:

    * If your project’s tests extend from `App\Tests\TestCase`, set:

      ```neon
      parameters:
          aryeo:
              tests:
                  testCaseClass: App\Tests\TestCase
      ```
    * If you want to **disable** all PHPStan test rules, set:

      ```neon
      parameters:
          aryeo:
              tests:
                  enabled: false
      ```
    * Leaving `aryeo.tests.enabled` out entirely will default to `true` (because the package’s `rules.neon` has `enabled: true` by default), so you only need to override it when you want it off.

4. **Run PHPStan** as usual:

   ```bash
   vendor/bin/phpstan analyse -c phpstan.neon.dist
   ```

    * If you kept the default `testCaseClass: Tests\TestCase` but your project uses a different namespace, you will see errors like:

      ```
      ClassAryeo\PHPStan\Rules\Tests\ClassMustExtendTestCaseRule:
      Class App\Tests\SomeTest does not extend Tests\TestCase
      ```

      In that case, update `aryeo.tests.testCaseClass` accordingly.

---

## Rule Descriptions & Examples

Below is a quick summary of what each rule does, plus usage examples.

### `Aryeo\PHPStan\Rules\Enums\EnumCasePascalCaseRule`

* **Always active** (no conditional toggle).
* **Enforces**: Every Enum `case` name must be PascalCase.
* **Why?** PHPStan’s default `EnumCase` rule focuses on visibility or value uniqueness, but this custom rule ensures stylistic consistency across your codebase.
* **Example Violation**:

  ```php
  enum Status: string
  {
      case pending;         // invalid: “pending” is not PascalCase
      case OrderPickedUp;   // invalid: starts with uppercase but next word not separated by capital
      case Shipped;         // valid if that’s exactly PascalCase (single‐word “Shipped”)
  }
  ```
* **Fix**:

  ```diff
  enum Status: string
  {
  -    case pending;
  -    case OrderPickedUp;
  +    case Pending;
  +    case OrderPickedUp;   // if you intended “OrderPickedUp” as PascalCase, ensure proper casing
      case Shipped;
  }
  ```

### `Aryeo\PHPStan\Rules\Tests\ClassMustExtendTestCaseRule`

* **Conditional**: Only runs when `aryeo.tests.enabled` = `true`.

* **Constructor Argument**: `$testCaseClass` (e.g., `'Tests\TestCase'` or `'App\Tests\TestCase'`).

* **Enforces**: Every class under your `tests/` directory (or any file matching `*Test.php`) must extend the given base test case class.

* **Why?** Some teams create a `BaseTestCase` or `TestCase` with common setup/teardown. This rule ensures no test accidentally extends `\PHPUnit\Framework\TestCase` directly or some other class.

* **Example Violation**:

  ```php
  // Assume: aryeo.tests.testCaseClass = 'App\Tests\TestCase'
  namespace App\Tests\Feature;

  use PHPUnit\Framework\TestCase;

  class UserTest extends TestCase
  {
      public function testSomething()
      {
          $this->assertTrue(true);
      }
  }
  ```

    * **Error**: `Class UserTest does not extend App\Tests\TestCase.`

* **Fix**:

  ```diff
  use PHPUnit\Framework\TestCase;

  class UserTest extends TestCase
  ```

  to:

  ```diff
  use App\Tests\TestCase;  // your own base test case

  class UserTest extends TestCase
  ```

### `Aryeo\PHPStan\Rules\Tests\MethodNamePrefixRule`

* **Conditional**: Only runs when `aryeo.tests.enabled` = `true`.
* **Constructor Argument**: `$testCaseClass`.
* **Enforces**: In any class extending `$testCaseClass`, all public test methods must start with a given prefix (e.g., `test`).
* **Why?** PHPUnit’s default behavior is to look for methods that start with `test`, but you may have configured fallback annotations, or you want to enforce a uniform method naming convention. This rule ensures that any test method is named `testSomething…` rather than `somethingTest()` or `it_does_something()`.
* **Example Violation**:

  ```php
  class UserTest extends App\Tests\TestCase
  {
      public function doesSomething() // invalid: missing “test” prefix
      {
          $this->assertTrue(true);
      }

      public function testItWorks() // valid
      {
          $this->assertTrue(true);
      }
  }
  ```
* **Fix**: Rename method to:

  ```php
  public function testDoesSomething()
  {
      $this->assertTrue(true);
  }
  ```

### `Aryeo\PHPStan\Rules\Tests\MethodNameSnakeCaseRule`

* **Conditional**: Only runs when `aryeo.tests.enabled` = `true`.
* **Constructor Argument**: `$testCaseClass`.
* **Enforces**: Test method names in classes extending `$testCaseClass` must use `snake_case` rather than `camelCase` or `PascalCase`.
* **Why?** Some teams prefer writing test names in snake\_case so that method names—when read—describe behavior in a consistent way (e.g., `test_user_can_register`). If your team enforces snake\_case, this rule flags camelCase or PascalCase.
* **Example Violation**:

  ```php
  class UserTest extends App\Tests\TestCase
  {
      public function testUserCanRegister() // invalid: camelCase/PascalCase
      {
          $this->assertTrue(true);
      }

      public function test_user_can_login()  // valid: snake_case
      {
          $this->assertTrue(true);
      }
  }
  ```
* **Fix**: Convert to snake\_case:

  ```diff
  - public function testUserCanRegister()
  + public function test_user_can_register()
  {
      $this->assertTrue(true);
  }
  ```

