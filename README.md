# Pwny plugin for Craft CMS

Enhance your site's security by ensuring users select stronger passwords than `password`. The plugin employs the k-Anonymity method to validate passwords against the Pwned Passwords API without compromising user privacy by revealing passwords to an external service.

This plugin was inspired by the Cloudflare blog post [Validating Leaked Passwords with k-Anonymity](https://blog.cloudflare.com/validating-leaked-passwords-with-k-anonymity/) and this one by [Troy Hunt](https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/) creator of the [Have I Been Pwnd](https://haveibeenpwned.com/?ref=troyhunt.com) service.

![Error message](./images/error.png)

## Requirements

This plugin requires Craft CMS `5.3.0` or later.

The plugin needs to make a call to the free [Have I Been Pwnd API](https://haveibeenpwned.com/API/v3#SearchingPwnedPasswordsByRange) at the address `https://api.pwnedpasswords.com/` in case you need to whitelist this on your host.

## Installation

To install the plugin, follow these instructions:

```
composer require "simplygoodwork/craft-pwny:^1.0.0" -w && php craft plugin/install pwny
```

for DDEV users:

```
ddev composer require "simplygoodwork/craft-pwny:^1.0.0" -w && ddev craft plugin/install pwny
```

## Settings

You can toggle the service and customise the error message.

![Settings](./images/settings.png)

---

Brought to you by [Good Work](https://simplygoodwork.com).
