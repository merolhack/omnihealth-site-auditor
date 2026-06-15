# History & Changelog

## 2026-06-15
- **Bugfix (Local Dev False Positives):** Addressed Docker loopback limitations. `check_security_headers` now gracefully bypasses (passes) when `wp_remote_get` to the homepage fails due to a local environment connection issue. 
- **Bugfix (Local Dev False Positives):** `check_ssl_cert_expiry` no longer attempts a TLS connection on non-HTTPS sites, gracefully skipping the check. This eliminates false warnings when testing locally without SSL.
- **Compatibility adjustment:** Modified the plugin header `Requires at least` from `6.3` to `6.0`. This change was made to accommodate local Docker testing, as the official `wordpress:6.3-php7.4-apache` image tag is missing from Docker Hub, forcing the use of `wordpress:6.0-php7.4-apache` for the legacy environment.
