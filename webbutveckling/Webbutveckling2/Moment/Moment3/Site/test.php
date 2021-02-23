<?php

function foo(array $bar = []) {
    array_push($bar, "spam");
    return $bar;
}

foo();
foo();
echo var_dump(foo()) ."<br>";
