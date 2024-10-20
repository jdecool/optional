Optional
========

A PHP implementation of the Optional pattern, inspired by Java's [`java.util.Optional`](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html).

This library provides a container object which may or may not contain a non-null value, helping to avoid null pointer exceptions and improve code readability.

## Installation

### Using Composer (recommended)

You can install this library using Composer. Run the following command in your project directory:

```bash
composer require jdecool/optional
```

### Manual Installation

If you're not using Composer, you can download the library files and include them manually in your project. Make sure to set up the appropriate autoloading for the `JDecool\DataStructure` namespace.

## Usage

### Creating an Optional

#### `Optional::of($value)`

Creates an Optional with a non-null value.

```php
use JDecool\DataStructure\Optional;

$optional = Optional::of('Hello, World!');
```

#### `Optional::ofNullable($value)`

Creates an Optional that may contain a null value.

```php
$optional = Optional::ofNullable(null);
```

#### `Optional::empty()`

Creates an empty Optional.

```php
$emptyOptional = Optional::empty();
```

### Checking the Optional's State

#### `ifPresent(callable $action)`

Performs an action if the Optional contains a non-null value;

```php
$optional->ifPresent(static fn ($value) => echo "Optional contains a value.");
```

#### `ifPresentOrElse(callable $action, callable $emptyAction)`

Performs the given action with the value, otherwise performs the given empty-based action.


```php
$optional->ifPresentOrElse(
  static fn ($value) => echo "Optional contains a value.",
  static fn () => echo "Optional doesn't contains a value.",
);
```


#### `isPresent()`

Checks if the Optional contains a non-null value.

```php
if ($optional->isPresent()) {
    echo "Value is present";
}
```

#### `isEmpty()`

Checks if the Optional is empty (contains null).

```php
if ($optional->isEmpty()) {
    echo "Optional is empty";
}
```

### Retrieving the Value

#### `get()`

Retrieves the value if present, throws a `NoSuchElementException` if empty.

```php
try {
    $value = $optional->get();
} catch (NoSuchElementException $e) {
    echo "No value present";
}
```

### Transforming and Filtering

#### `filter(callable $predicate)`

Filters the Optional based on a predicate.

```php
$filtered = $optional->filter(fn($value) => strlen($value) > 5);
```

#### `map(callable $mapper)`

Transforms the Optional's value using a mapping function.

```php
$mapped = $optional->map(fn($value) => strtoupper($value));
```

### Providing Fallback Values

#### `or($other)`

Returns the Optional if it has a value, otherwise returns the provided Optional.

```php
$result = $optional->or(Optional::of('Default'));
```

#### `orElse($other)`

Returns the Optional's value if present, otherwise returns the provided value.

```php
$value = $optional->orElse('Default');
```

#### `orElseThrow(Throwable $exception)`

Returns the Optional's value if present, otherwise throws the provided exception.

```php
try {
    $value = $optional->orElseThrow(new Exception('Value not present'));
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Comparing Optionals

#### `equals(mixed $object)`

Compares this Optional to another object for equality.

```php
$isEqual = $optional->equals(Optional::of('Hello, World!'));
```

This Optional library provides a robust way to handle potentially null values in your PHP code, reducing the risk of null pointer exceptions and improving overall code quality.
