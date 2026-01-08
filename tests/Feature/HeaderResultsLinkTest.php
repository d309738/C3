<?php

it('shows results link in header on guest pages', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Bekijk resultaten');
});
