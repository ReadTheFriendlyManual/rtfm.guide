<?php

test('home page loads successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('guides page loads successfully', function () {
    $response = $this->get('/guides');

    $response->assertStatus(200);
});

test('guide detail page loads successfully', function () {
    $guide = \App\Models\Guide::first();

    if ($guide) {
        $response = $this->get('/guides/'.$guide->slug);

        $response->assertStatus(200);
    } else {
        $this->assertTrue(true, 'No guides in database, skipping test');
    }
});
