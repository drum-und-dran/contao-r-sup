# Contao R Sup

Replaces occurrences of `(R)`, `(r)` and `®` with a superscript `<sup>®</sup>` in the Contao frontend output.

---

## Features

- Converts `(R)` and `(r)` to `®`
- Wraps all `®` characters in `<sup>` tags
- DOM-based processing (safe for HTML structure)
- Ignores `<script>` and `<style>` elements
- Works across:
  - content elements
  - navigation
  - frontend modules

---

## Requirements

- Contao 5.7+
- PHP 8.2+

---

## Installation

Install via Composer:

```bash
composer require dud/contao-r-sup
