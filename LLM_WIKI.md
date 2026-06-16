# OmniHealth Site Auditor — LLM Wiki

Welcome to the internal Knowledge Base for the OmniHealth Site Auditor! This document is explicitly written to bootstrap future AI/LLM assistants entering this repository, providing them with the necessary context, architectural rules, and operational reality of the project.

## Project Overview
**OmniHealth Site Auditor** is a robust, extensible diagnostic WordPress plugin designed to uncover misconfigurations, security vulnerabilities, and performance bottlenecks that standard health checks often miss.

It ranks probes into three tiers:
- **Tier 1 (Critical)**: Immediate action required (e.g., exposed credentials).
- **Tier 2 (Recommended)**: High-impact improvements (e.g., outdated software).
- **Tier 3 (Good to have)**: Best practices and optimizations.

## Core Architectural Patterns

### The `OHSA_Engine`
All diagnostic logic is centrally processed by `includes/class-ohsa-engine.php`. 
- You should almost never modify the UI code (`class-ohsa-admin.php`) to add new checks.
- New checks are registered in the `$core` array inside the `register_core_checks()` method.
- The system heavily relies on the WordPress Hook API. Probes are dynamically loaded via the `ohsa_registered_checks` filter.

### The Probe Interface
A probe callback method must return an associative array exactly like this:
```php
return array(
    'status' => 'warn', // Must be 'pass', 'warn', or 'fail'
    'detail' => __( 'A clear, translated explanation.', 'omnihealth-site-auditor' ),
);
```

### i18n Translation Rules
The `wp plugin check` (PCP) scanner runs against this repository. It is merciless.
- If you use `sprintf( __( 'Message with %s', 'omnihealth-site-auditor' ), $var )`...
- You **MUST** put `/* translators: %s: what the variable is */` exactly on the line above the `sprintf`/`__` call.

## Development & Testing Workflow

1. **Local tests are broken**: Do NOT rely on standard `composer test` or `npm run test` inside the WSL environment. They will fail due to environment/pathing issues.
2. **Docker is King**: The correct way to validate PCP compliance is via the running Docker container:
   ```bash
   docker compose exec wp-latest wp plugin check omnihealth-site-auditor --allow-root
   ```
3. **CI acts as the final judge**: The GitHub Actions workflow tests against PHP 7.4 through 8.3. If CI fails, your PR/commit fails.
4. **Pushing Code**: The interactive prompt hangs the terminal. You MUST use the Personal Access Token (PAT) format to push code:
   ```bash
   git push https://merolhack:<PAT>@github.com/merolhack/omnihealth-site-auditor.git
   ```

## Release Workflow
Do not blindly `zip` the directory. The plugin relies on a strict `.distignore` file. You must follow the `omnihealth-release-workflow` SKILL (which uses a Python script to respect the `.distignore`) to package the plugin properly before deployment.

## Current State
- ✅ All **P1, P2, and P3** probes from the initial specification (`TODO.md`) have been implemented and verified.
- The UI features anchor-linked summary pills for smooth navigation.
- The focus is now shifting towards **Engineering / release** tasks (versioning, SVN deployment to WordPress.org, data migrations).

> **AI Instruction**: If you are a new agent reading this, acknowledge these rules and proceed confidently. You have everything you need!
