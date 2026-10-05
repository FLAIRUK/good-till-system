# Changelog

All notable changes to `good-till-system` will be documented in this file.

## 1.0.0 - 2026-10-05

Complete rewrite for Laravel 12 and 13 (PHP 8.2+). See the upgrade guide in the README.

- Token management: one login, cached JWT, proactive refresh before the 12-hour expiry, re-login on refresh failure or a revoked token.
- Resource classes covering products (incl. variants, duplicate, inventory), brands, categories, tags, suppliers, customer groups, customers (sales, loyalty points, prepayments), staff, staff clock records, users, outlets, registers, VAT rates, payment types, ingredients, promotions, vouchers, sales, external sales, reports and e-commerce stock.
- `GoodTill::forOutlet()` and a default `GOOD_TILL_OUTLET_ID` for the `Outlet-Id` header.
- `GoodTillException` / `AuthenticationException` for HTTP errors and `{"status": false}` responses.
- GET requests retried on connection errors and 5xx; writes never retried.
- `goodtill:install` (config + `.env` keys) and `goodtill:status` (connection check) commands.
- Test suite and GitHub Actions CI, replacing Travis, CircleCI, Scrutinizer and StyleCI.
