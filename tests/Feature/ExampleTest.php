<?php

test('the home page redirects to the cases page', function () {
    $response = $this->get('/');

    $response->assertRedirect('/cases');
});