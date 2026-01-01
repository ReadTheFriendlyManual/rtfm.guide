<?php

use App\Models\Guide;
use App\Models\ShareLink;

test('can generate share links for a guide', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    $response = $this->post("/api/guides/{$guide->id}/share-links");

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'sfw',
        'nsfw',
    ]);

    expect($guide->shareLinks()->count())->toBe(2);
    expect($guide->shareLinks()->where('mode', 'sfw')->exists())->toBeTrue();
    expect($guide->shareLinks()->where('mode', 'nsfw')->exists())->toBeTrue();
});

test('share links are reused if they already exist', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    $firstResponse = $this->post("/api/guides/{$guide->id}/share-links");
    $firstUrls = $firstResponse->json();

    $secondResponse = $this->post("/api/guides/{$guide->id}/share-links");
    $secondUrls = $secondResponse->json();

    expect($firstUrls['sfw'])->toBe($secondUrls['sfw']);
    expect($firstUrls['nsfw'])->toBe($secondUrls['nsfw']);
    expect($guide->shareLinks()->count())->toBe(2);
});

test('sfw share link redirects to guide and sets sfw mode for guests', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $shareLink = ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);

    $response = $this->get("/share/{$shareLink->token}");

    $response->assertRedirect("/guides/{$guide->slug}");
    $response->assertCookie('rtfm_mode', 'sfw');
});

test('nsfw share link redirects to guide and sets nsfw mode for guests', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $shareLink = ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'nsfw',
    ]);

    $response = $this->get("/share/{$shareLink->token}");

    $response->assertRedirect("/guides/{$guide->slug}");
    $response->assertCookie('rtfm_mode', 'nsfw');
});

test('share link respects existing user mode preference', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $shareLink = ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);

    $response = $this->withCookie('rtfm_mode', 'nsfw')
        ->get("/share/{$shareLink->token}");

    $response->assertRedirect("/guides/{$guide->slug}");
    $response->assertCookieMissing('rtfm_mode');
});

test('share link increments visit count', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $shareLink = ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);

    expect($shareLink->visit_count)->toBe(0);

    $this->get("/share/{$shareLink->token}");

    expect($shareLink->fresh()->visit_count)->toBe(1);

    $this->get("/share/{$shareLink->token}");

    expect($shareLink->fresh()->visit_count)->toBe(2);
});

test('invalid share token returns 404', function () {
    $response = $this->get('/share/invalid-token');

    $response->assertNotFound();
});

test('share links have unique tokens', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    $token1 = ShareLink::generateToken();
    $token2 = ShareLink::generateToken();

    expect($token1)->not->toBe($token2);
    expect(strlen($token1))->toBe(16);
    expect(strlen($token2))->toBe(16);
});

test('cannot create duplicate share links for same guide and mode', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);
});

test('deleting guide cascades to share links', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'sfw',
    ]);

    ShareLink::create([
        'guide_id' => $guide->id,
        'token' => ShareLink::generateToken(),
        'mode' => 'nsfw',
    ]);

    expect(ShareLink::count())->toBe(2);

    $guide->delete();

    expect(ShareLink::count())->toBe(0);
});
