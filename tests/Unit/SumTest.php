<?php

it('sum', function () {
    $a = array(2, 4, 6, 8);
    $result = array_sum($a);

    expect($result)->toBe(20);
});
