# RTFM.guide Style Guidelines

This document outlines the design system and style conventions for RTFM.guide to ensure visual consistency across the application.

## Color Palette

### Primary Colors

**Wine/Burgundy** - Sophisticated primary color
- `wine-400`: #e879b9
- `wine-500`: #db2777 (primary)
- `wine-600`: #be185d
- `wine-700`: #9f1239
- `wine-800`: #881337
- `wine-900`: #701a2e

**Gold** - Luxurious accent
- `gold-400`: #fcd34d
- `gold-500`: #fbbf24
- `gold-600`: #f59e0b (primary)
- `gold-700`: #d97706

**Sage Green** - Fresh, modern secondary
- `sage-400`: #86efac
- `sage-500`: #4ade80
- `sage-600`: #22c55e (primary)
- `sage-700`: #16a34a
- `sage-800`: #15803d

### Neutral Colors

**Pearl** - Warm gray neutrals
- `pearl-50`: #fafaf9 (light backgrounds)
- `pearl-100`: #f5f5f4
- `pearl-200`: #e7e5e4
- `pearl-300`: #d4d4d3
- `pearl-400`: #a3a3a2
- `pearl-500`: #737372
- `pearl-600`: #525251
- `pearl-700`: #404040
- `pearl-800`: #262626
- `pearl-900`: #171717 (dark backgrounds)
- `pearl-950`: #0a0a0a

## Typography

### Font Families

**Display Font** - `Bricolage Grotesque`
- Use for: Headlines, brand elements, section titles
- Weights: 400, 500, 600, 700, 800
- Character: Bold, geometric, unique

**Body Font** - `Onest`
- Use for: Body text, UI elements, labels
- Weights: 300, 400, 500, 600, 700
- Character: Clean, modern, readable

### Usage Examples

```vue
<!-- Headlines -->
<h1 class="font-display text-5xl/tight font-bold">Headline</h1>

<!-- Subheadings -->
<h2 class="font-display text-3xl/tight font-bold">Subheading</h2>

<!-- Body text -->
<p class="text-base/relaxed">Regular body text with relaxed line height</p>

<!-- Labels -->
<label class="text-sm/tight font-semibold">Form Label</label>
```

### Line Height Convention

Always use line height modifiers with text sizes (e.g., `text-base/relaxed`, `text-xl/tight`):
- `/tight` - Compact spacing for headlines
- `/relaxed` - Comfortable spacing for body text
- `/loose` - Extra spacing when needed

## Color Usage Patterns

### Gradients

The signature gradient combines wine → gold:

```vue
<!-- Text gradient -->
<span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent">
  Gradient Text
</span>

<!-- Background gradient -->
<div class="bg-linear-to-br from-wine-600 to-wine-700">
  Content
</div>
```

### Backgrounds

**Light Mode:**
- Page background: `bg-pearl-50` or `bg-white`
- Card background: `bg-white` or `bg-pearl-50`
- Elevated cards: `bg-white/90` with `backdrop-blur-xl`

**Dark Mode:**
- Page background: `bg-pearl-950` or `bg-pearl-900`
- Card background: `bg-pearl-900` or `bg-pearl-800`
- Elevated cards: `bg-pearl-900/90` with `backdrop-blur-xl`

### Text Colors

**Light Mode:**
- Primary text: `text-pearl-900`
- Secondary text: `text-pearl-600`
- Muted text: `text-pearl-500`

**Dark Mode:**
- Primary text: `text-pearl-50`
- Secondary text: `text-pearl-400`
- Muted text: `text-pearl-500`

### Interactive Elements

**Links:**
```vue
<a class="text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors">
  Link Text
</a>
```

**Buttons:**
- Primary: Wine gradient background
- Secondary: Gold border with transparent background
- Ghost: Subtle pearl background on hover

## Component Patterns

### Cards

```vue
<!-- Standard card -->
<div class="border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6 bg-white/80 dark:bg-pearl-800/50 backdrop-blur-sm">
  Card content
</div>

<!-- Interactive card -->
<div class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
            hover:border-wine-400 dark:hover:border-wine-600
            transition-all duration-300
            hover:shadow-lg hover:shadow-wine-600/10">
  Interactive card content
</div>
```

### Form Inputs

```vue
<input
  class="w-full px-4 py-2.5 rounded-xl
         border-2 border-pearl-300 dark:border-pearl-600
         bg-white dark:bg-pearl-800
         text-pearl-900 dark:text-pearl-50
         focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500
         transition-all
         placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
/>
```

### Buttons

```vue
<!-- Primary button -->
<button class="bg-linear-to-r from-wine-600 to-wine-700
               hover:from-wine-500 hover:to-wine-600
               text-white font-semibold
               px-8 py-3 rounded-2xl
               shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40
               transition-all duration-200">
  Primary Action
</button>

<!-- Secondary button -->
<button class="bg-transparent border-2 border-gold-600
               text-gold-700 dark:text-gold-500
               hover:bg-gold-600/10
               font-semibold px-8 py-3 rounded-2xl
               transition-all duration-200">
  Secondary Action
</button>
```

## Spacing & Layout

### Border Radius

- Small elements (buttons, inputs): `rounded-xl` (12px)
- Large elements (cards, modals): `rounded-2xl` (16px)
- Checkboxes, small UI: `rounded-lg` (8px)

### Borders

- Standard borders: `border-2` (2px)
- Subtle dividers: `border-t-2` or `border-b-2`
- Interactive elements should use thicker borders for emphasis

### Shadows

```vue
<!-- Subtle shadow -->
shadow-sm

<!-- Standard shadow -->
shadow-lg shadow-wine-600/10

<!-- Interactive shadow -->
hover:shadow-xl hover:shadow-wine-600/10

<!-- Button shadow -->
shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40
```

## Animations

### Available Animations

**Fade In Up** - For sequential content reveals
```vue
<div class="animate-fade-in-up" style="animation-delay: 0.1s;">
  Content
</div>
```

**Scale In** - For modal/card entrances
```vue
<div class="animate-scale-in">
  Content
</div>
```

**Float** - For subtle floating elements
```vue
<div class="animate-float">
  Floating element
</div>
```

**Shimmer** - For gradient text effects
```vue
<span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent animate-shimmer">
  Shimmering text
</span>
```

### Animation Timing

- Standard transitions: `transition-all duration-200`
- Interactive elements: `transition-colors` or `transition-all`
- Hover effects: `duration-300`
- Use `cubic-bezier(0.16, 1, 0.3, 1)` for smooth, elegant motion

## Patterns & Textures

### Grid Pattern

```vue
<div class="pattern-grid text-pearl-200/20 dark:text-pearl-800/20">
  <!-- Content -->
</div>
```

### Grain Texture

```vue
<div class="texture-grain">
  <!-- Content -->
</div>
```

Use these subtly in backgrounds for depth and sophistication.

## Responsive Design

### Breakpoint Usage

```vue
<!-- Mobile first approach -->
<div class="text-base sm:text-lg md:text-xl lg:text-2xl">
  Responsive text
</div>

<!-- Spacing -->
<div class="px-4 sm:px-6 lg:px-8">
  Responsive padding
</div>
```

### Container Widths

Use `max-w-7xl` for main content containers:
```vue
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  Main content
</div>
```

## Dark Mode

### Implementation

Always include dark mode variants:
```vue
<div class="bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50">
  Content
</div>
```

### Color Selection

- Maintain contrast ratios for accessibility
- Use lighter shades in dark mode for accents
- Wine/gold/sage colors work in both modes with appropriate shade adjustments

## Accessibility

### Focus States

Always include visible focus states:
```vue
<button class="focus:ring-3 focus:ring-wine-500 focus:ring-offset-2">
  Button
</button>
```

### Color Contrast

- Ensure text meets WCAG AA standards (4.5:1 for body, 3:1 for large text)
- Test dark mode separately
- Wine/pearl combinations provide good contrast

## Examples

### Feature Card

```vue
<div class="group p-8 rounded-2xl
            bg-linear-to-br from-wine-50/50 to-wine-100/30
            dark:from-pearl-900 dark:to-pearl-800
            border-2 border-wine-200 dark:border-pearl-700
            hover:border-wine-400 dark:hover:border-wine-700
            hover:shadow-xl hover:shadow-wine-600/10
            transition-all duration-300">
  <div class="size-12
              bg-linear-to-br from-wine-600 to-wine-700
              rounded-xl
              flex items-center justify-center
              shadow-lg shadow-wine-600/40
              group-hover:scale-110 transition-transform duration-300
              mb-4">
    <!-- Icon -->
  </div>
  <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
    Feature Title
  </h3>
  <p class="text-pearl-600 dark:text-pearl-400">
    Feature description text
  </p>
</div>
```

### Stat Card

```vue
<div class="border-2 border-pearl-200 dark:border-pearl-700
            rounded-2xl p-6
            bg-white/80 dark:bg-pearl-800/50 backdrop-blur-sm
            hover:border-wine-400 dark:hover:border-wine-600
            transition-all duration-300
            hover:shadow-lg hover:shadow-wine-600/10">
  <p class="font-display text-4xl/tight font-bold
            bg-linear-to-br from-wine-600 to-wine-700
            bg-clip-text text-transparent">
    500+
  </p>
  <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mt-1 font-medium">
    Guides
  </p>
</div>
```

## Don'ts

❌ Don't use arbitrary colors outside the palette
❌ Don't mix border widths (stick to `border-2`)
❌ Don't forget dark mode variants
❌ Don't use `outline-none` without custom focus styles
❌ Don't use purple/blue gradients (avoid generic AI aesthetic)
❌ Don't use system fonts (use Bricolage Grotesque & Onest)
❌ Don't use `min-h-screen` (use `min-h-dvh` for mobile compatibility)
❌ Don't use separate `w-*` and `h-*` for equal dimensions (use `size-*`)

## Checklist for New Components

- [ ] Uses pearl neutrals for base colors
- [ ] Includes wine/gold/sage for accents
- [ ] Has both light and dark mode variants
- [ ] Uses Bricolage Grotesque for headings
- [ ] Uses Onest for body text
- [ ] Includes appropriate hover/focus states
- [ ] Uses `rounded-xl` or `rounded-2xl` for borders
- [ ] Includes smooth transitions (`duration-200` or `duration-300`)
- [ ] Maintains accessibility (focus states, contrast)
- [ ] Follows mobile-first responsive patterns
