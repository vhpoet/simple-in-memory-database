Simple in memory database
=========================

This is a very simple in-memory database, which has a very limited command set.

- **SET [name] [value]**: Set a variable [name] to the value [value]. Neither variable names or values will ever contain spaces.
- **GET [name]**: Print out the value stored under the variable [name]. Print NULL if that variable name hasn't been set.
- **UNSET [name]**: Unset the variable [name]
- **NUMEQUALTO [value]**: Return the number of variables equal to [value]. If no values are equal, this should output 0.
- **END**: Exit the program
- **BEGIN**: Open a transactional block
- **ROLLBACK**: Rollback all of the commands from the most recent transaction block. If no transactional block is open, print out INVALID ROLLBACK
- **COMMIT**: Permanently store all of the operations from any presently open transactional blocks

Usage examples
-
#### Example 1

    $ php cli.php 
    SET a 10
    GET a
    UNSET a
    GET a
    END
    -- OUTPUT --
    10
    NULL

#### Example 2

    $ php cli.php 
    SET a 10
    SET b 10
    NUMEQUALTO 10
    NUMEQUALTO 20
    UNSET a
    NUMEQUALTO 10
    SET b 30
    NUMEQUALTO 10
    END
    -- OUTPUT --
    2
    0
    1
    0

#### Example 3

    $ php cli.php 
    BEGIN
    SET a 10
    GET a
    BEGIN
    SET a 20
    GET a
    ROLLBACK
    GET a
    ROLLBACK
    GET a
    END
    -- OUTPUT --
    10
    20
    10
    NULL

#### Example 4

    $ php cli.php 
    BEGIN
    SET a 30
    BEGIN
    SET a 40
    COMMIT
    GET a
    ROLLBACK
    END
    -- OUTPUT --
    40
    INVALID ROLLBACK

#### Example 5

    $ php cli.php 
    SET a 50
    BEGIN
    GET a
    SET a 60
    BEGIN
    UNSET a
    GET a
    ROLLBACK
    GET a
    COMMIT
    GET a
    END
    -- OUTPUT --
    50
    NULL
    60
    60

#### Example 6

    $ php cli.php 
    SET a 10
    BEGIN
    NUMEQUALTO 10
    BEGIN
    UNSET a
    NUMEQUALTO 10
    ROLLBACK
    NUMEQUALTO 10
    END
    -- OUTPUT --
    1
    0
    1
