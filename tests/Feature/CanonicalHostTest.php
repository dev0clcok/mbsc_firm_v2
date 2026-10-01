<?php

/**
 * Each page must be reachable on one host name only, the one in APP_URL,
 * so the "www" and bare addresses are not indexed as duplicates.
 */
beforeEach(function () {
    config(['app.url' => 'https://www.example.com']);
});

test('the bare domain redirects permanently to the configured host', function () {
    $this->get('http://example.com/services?page=2')
        ->assertStatus(301)
        ->assertRedirect('https://www.example.com/services?page=2');
});

test('the home page on the bare domain redirects to the configured host', function () {
    $this->get('https://example.com/')
        ->assertStatus(301)
        ->assertRedirect('https://www.example.com/');
});

test('the www address redirects when the configured host is the bare domain', function () {
    config(['app.url' => 'https://example.com']);

    $this->get('https://www.example.com/contact')
        ->assertStatus(301)
        ->assertRedirect('https://example.com/contact');
});

test('the configured host is served without a redirect', function () {
    $this->get('https://www.example.com/services')->assertOk();
});

test('the host name is compared without regard to case', function () {
    $this->get('https://WWW.EXAMPLE.COM/services')->assertOk();
});

test('unrelated hosts are left alone', function (string $host) {
    $this->get("http://{$host}/services")->assertOk();
})->with(['localhost', '127.0.0.1', 'staging.example.com', 'example.org']);

test('form submissions are not redirected', function () {
    $response = $this->post('https://example.com/contact', []);

    expect($response->status())->not->toBe(301);
});
