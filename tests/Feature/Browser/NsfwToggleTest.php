<?php

it('toggles between SFW and NSFW modes', function () {
    $page = visit('/');

    // Initially should be in SFW mode
    $page->assertSee('SFW')
        ->assertSee('NSFW');

    // Get the initial message (should be SFW)
    $initialMessage = $page->script('
        (() => {
            const messageElement = document.querySelector("p.text-amber-700, p.dark\\\\:text-amber-300");
            return messageElement ? messageElement.textContent : null;
        })()
    ');

    expect($initialMessage)->not->toBeNull();

    // The initial message should not contain profanity
    expect($initialMessage)->not->toContain('dumbass');
    expect($initialMessage)->not->toContain('fucking');

    // Click the NSFW toggle (click the switch button inside the toggle container)
    $page->click('[data-toggle-mode] button')
        ->wait(500); // Wait for the mode to update

    // Get the new message after toggling
    $nsfwMessage = $page->script('
        (() => {
            const messageElement = document.querySelector("p.text-amber-700, p.dark\\\\:text-amber-300");
            return messageElement ? messageElement.textContent : null;
        })()
    ');

    // Message should have changed
    expect($nsfwMessage)->not->toBe($initialMessage);

    // Toggle back to SFW
    $page->click('[data-toggle-mode] button')
        ->wait(500);

    $sfwMessageAgain = $page->script('
        (() => {
            const messageElement = document.querySelector("p.text-amber-700, p.dark\\\\:text-amber-300");
            return messageElement ? messageElement.textContent : null;
        })()
    ');

    // Should not contain profanity again
    expect($sfwMessageAgain)->not->toContain('dumbass');
    expect($sfwMessageAgain)->not->toContain('fucking');

    // Verify no JavaScript errors
    $page->assertNoJavascriptErrors();
});

it('persists mode preference in localStorage', function () {
    $page = visit('/');

    // Toggle to NSFW
    $page->click('[data-toggle-mode] button')
        ->wait(500);

    // Check localStorage
    $storedMode = $page->script('localStorage.getItem("rtfm_mode")');
    expect($storedMode)->toBe('nsfw');

    // Reload the page
    $page->navigate('/');

    // Mode should still be NSFW
    $storedModeAfterReload = $page->script('localStorage.getItem("rtfm_mode")');
    expect($storedModeAfterReload)->toBe('nsfw');

    // Clean up - toggle back to SFW
    $page->click('[data-toggle-mode] button');
});

it('regenerates message when clicking refresh icon', function () {
    $page = visit('/');

    // Get initial message
    $initialMessage = $page->script('
        (() => {
            const messageElement = document.querySelector("p.text-amber-700, p.dark\\\\:text-amber-300");
            return messageElement ? messageElement.textContent : null;
        })()
    ');

    // Click the refresh icon
    $page->click('button[aria-label="Get new message"]')
        ->wait(200);

    // Get new message
    $newMessage = $page->script('
        (() => {
            const messageElement = document.querySelector("p.text-amber-700, p.dark\\\\:text-amber-300");
            return messageElement ? messageElement.textContent : null;
        })()
    ');

    // Message might be the same (random), but at least verify it's still there
    expect($newMessage)->not->toBeNull();

    $page->assertNoJavascriptErrors();
});
