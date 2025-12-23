<?php

it('displays login page with correct elements', function () {
    $page = visit('/login');

    $page->assertSee('Welcome Back')
        ->assertSee('Sign in to your account to continue')
        ->assertSee('Email')
        ->assertSee('Password')
        ->assertSee('Remember me')
        ->assertSee('Forgot password?')
        ->assertSee('Sign In')
        ->assertSee("Don't have an account?")
        ->assertSee('Sign up')
        ->assertNoJavascriptErrors();
});

it('displays register page with correct elements', function () {
    $page = visit('/register');

    $page->assertSee('Join RTFM.guide')
        ->assertSee('Create your account to get started')
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('Password')
        ->assertSee('Confirm Password')
        ->assertSee('Create Account')
        ->assertSee('Already have an account?')
        ->assertSee('Sign in')
        ->assertNoJavascriptErrors();
});

it('navigates between login and register pages', function () {
    $page = visit('/login');

    // Click sign up link
    $page->click('a[href="/register"]')
        ->assertSee('Join RTFM.guide');

    // Click sign in link
    $page->click('a[href="/login"]')
        ->assertSee('Welcome Back');

    $page->assertNoJavascriptErrors();
});

it('shows back to home link on auth pages', function () {
    $page = visit('/login');

    // Click the "Back to home" link specifically (not the logo)
    $page->assertSee('Back to home')
        ->click('text=Back to home')
        ->assertSee('Stop Googling');

    $page->assertNoJavascriptErrors();
});

it('auth pages support dark mode toggle', function () {
    $page = visit('/login');

    // Get the theme toggle button
    $isDarkMode = $page->script('document.documentElement.classList.contains("dark")');

    // Click theme toggle
    $page->click('button[aria-label*="mode"]')
        ->wait(200);

    // Check that dark mode toggled
    $newDarkMode = $page->script('document.documentElement.classList.contains("dark")');

    expect($newDarkMode)->not->toBe($isDarkMode);

    $page->assertNoJavascriptErrors();
});
