Optional
========

This is a port of the [`java.util.Optional`](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html) Java class to PHP.

A container object which may or may not contain a non-null value. If a value is present, isPresent() will return true and get() will return the value.

Additional methods that depend on the presence or absence of a contained value are provided, such as orElse() (return a default value if value not present) and ifPresent() (execute a block of code if the value is present).

This is a value-based class;
