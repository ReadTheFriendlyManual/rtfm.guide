# Browser Tests

Browser tests have been temporarily removed due to async event loop conflicts in Pest v4's browser plugin when running multiple tests.

## Issue
Tests fail with: "Must call resume() or throw() before calling suspend() again"

This is a known issue with Pest's browser testing in certain configurations.

## Workaround
- Write browser tests individually
- Run with `--headed` and `--debug` flags for development
- Consider using Playwright directly for complex browser testing

## Removed Tests
- NsfwToggleTest.php
- AuthPagesTest.php  
- HeroLayoutTest.php

These can be rewritten or run individually when needed.
