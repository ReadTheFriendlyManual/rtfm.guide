<?php

it('displays hero section at full viewport height with visible scroll arrow', function () {
    $page = visit('/');

    // Verify the hero section exists
    $page->assertSee('Stop Googling');

    // Get viewport height
    $viewportHeight = $page->script('window.innerHeight');

    // Get the scroll arrow element's position
    $arrowPosition = $page->script('
        (() => {
            const arrow = document.querySelector("svg").closest("div");
            const rect = arrow.getBoundingClientRect();
            return {
                bottom: rect.bottom,
                top: rect.top,
                isVisible: rect.bottom <= window.innerHeight && rect.top >= 0
            };
        })()
    ');

    // Arrow should be visible within the viewport (no scrolling needed)
    expect($arrowPosition['isVisible'])->toBeTrue(
        "Scroll arrow should be visible within viewport. Arrow bottom: {$arrowPosition['bottom']}, Viewport height: {$viewportHeight}"
    );

    // Arrow should be near the bottom of the viewport
    expect($arrowPosition['bottom'])->toBeLessThanOrEqual($viewportHeight);

    // Verify no JavaScript errors
    $page->assertNoJavascriptErrors();
});

it('hero section is mobile responsive', function () {
    $page = visit('/');

    // Resize to iPhone SE size
    $page->resize(375, 667);

    // Verify content is visible on mobile
    $page->assertSee('Stop Googling')
        ->assertSee('Browse Guides');

    // Arrow should still be visible on mobile
    $arrowVisible = $page->script('
        (() => {
            const arrow = document.querySelector("svg").closest("div");
            const rect = arrow.getBoundingClientRect();
            return rect.bottom <= window.innerHeight && rect.top >= 0;
        })()
    ');

    expect($arrowVisible)->toBeTrue('Scroll arrow should be visible on mobile viewport');

    $page->assertNoJavascriptErrors();
});
