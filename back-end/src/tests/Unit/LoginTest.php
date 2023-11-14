<?php

$expected_response_format = [
    'status',
    'success',
    'message',
    'data'
];

// * email e/ou senha não informado(s)
it('login endpoint, error case: email and password not provided', function() use ($expected_response_format) {
    $request = $this->call('POST', '/api/login', [])
    ->assertStatus(400)
    ->assertJsonStructure($expected_response_format);
});

// * e-mail e senha com formatos fora do padrão estabelecido
it('email and password with formats outside the established standard', function() use ($expected_response_format) {
    $request = $this->call('POST', '/api/login', [
        'email'    => 'a',
        'password' => 'b'
    ])
    ->assertStatus(400)
    ->assertJsonStructure($expected_response_format);
});

// * e-mail e senha com formatos fora do padrão estabelecido
it('email and password with formats outside the established standard (min chars)', function() use ($expected_response_format) {
    $request = $this->call('POST', '/api/login', [
        'email'    => 'a@a', // It needs to be an email in the correct format
        'password' => '12345' // needs 6+
    ])
    ->assertStatus(400)
    ->assertJsonStructure($expected_response_format);
});

// * Sucesso no login
it('successful login', function() {
    $request = $this->call('POST', '/api/login', [
        'email'    => 'dev@.investments',
        'password' => 'super_jedi'
    ])
    ->assertStatus(200)
    ->assertJsonStructure([
        "success",
        "status",
        "message",
        "data", // contains the token
    ]);
});
