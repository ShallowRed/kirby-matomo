# Kirby Matomo

This plugin helps you generate a tracking code for Matomo, and displays some useful metrics directly within the panel.

![matomo-screenshot](https://user-images.githubusercontent.com/14079751/47005802-d5300a00-d134-11e8-8e8e-ed4525c94bb3.jpg)

<br/>

## Overview

- [1. Why Matomo?](#1-why-matomo)
- [2. Installation](#2-installation)
- [3. Options](#3-options)
- [4. Template usage](#4-template-usage)
- [5. Panel setup](#5-panel-setup)
  * [5.1. Basic blueprint example](#51-basic-blueprint-example)
  * [5.2. Options](#52-options)
  * [5.3. Complete blueprint example](#53-complete-blueprint-example)
- [6. License](#6-license)
- [7. Credits](#7-credit)


#### TLDR – Just get me started 👀

1. Install Matomo on your server.
2. Copy the content of this repo within the `site/plugins` folder.
3. Set the following values in your `config.php` :
```php
return array(
    'sylvainjule.matomo.url'        => 'http://yourmatomourl.com',
    'sylvainjule.matomo.id'         => 'mywebsite',
    'sylvainjule.matomo.token'      => 'token_auth,
);
```
4. Add this code to your footer snippet: `<?php echo snippet('matomo') ?>`
5. Copy [this blueprint](#51-basic-blueprint-example) under a dedicated tab / page in the panel.

You're all set.

<br/>

## 1. Why Matomo?

- It's open-source (who doesn't like open-source?)
- It's free (like, really free. You don't pay with your data)
- It's self-hosted (which means more control for you over your data)
- It respects your visitors privacy (IP Anonymization, automated logs deletion, data ownership)
- It now integrates smoothly with Kirby 3 ✌️

<br/>

## 2. Installation

Download and copy this repository to ```/site/plugins/matomo```

Alternatively, you can install it with composer: ```composer require sylvainjule/matomo```

<br/>

## 3. Options

Here is an overview of the available options with their default values:

```php
return array(
    'sylvainjule.matomo.url'        => false, #required
    'sylvainjule.matomo.id'         => false, #required
    'sylvainjule.matomo.token'      => false, #required for the panel integration
    'sylvainjule.matomo.active'     => true,
    'sylvainjule.matomo.debug'      => false,
    'sylvainjule.matomo.trackUsers' => false,
    'sylvainjule.matomo.blacklist'  => ['127.0.0.1', '::1'],
);
```

#### 3.1. `url` (required)

Where your matomo install is:

```php
'sylvainjule.matomo.url' => 'https://analytics.yourdomain.com'
```

#### 3.2. `id` (required)

A single Matomo install can host multiple websites. The plugin needs to know the `id` of the one to look for: 

```php
'sylvainjule.matomo.id' => 'mywebsite'
```

#### 3.3. `token` (required for the panel integration)

The panel sections will need to make calls to your Matomo API. A `token_auth` is required, you will find it in `Settings > API` in the control panel. Copy-paste the string without the `&token_auth=` prefix.

> Below is an example token. Please note that this token is private and shouldn't be made public. Once added, if you need to publish your code please create something like a duplicated `config.github.php` which will contain the non-sensitive informations, and add your real `config.php` to your `.gitignore`.

```php
'sylvainjule.matomo.token' => 'gQ7TQgg8EFe3h29F9t4aJqC33VQdPfFP4M'
```

#### 3.4. `active`

You can deactivate the snippet by setting this value to `false`.

```php
'sylvainjule.matomo.active' => true
```

#### 3.5. `blacklist`

Localhost is in the blacklist as default. You can change it.

```php
'sylvainjule.matomo.blacklist' => ['127.0.0.1', '::1']
```

#### 3.6. `trackUsers`

The script is only active for not logged in users by default. If you want to change it, set this option to `true`.

```php
'sylvainjule.matomo.trackUsers' => false
```

#### 3.7. `debug`

If you want to always run the script (even on localhost or if you are logged in), set this option to `true`.

```php
'sylvainjule.matomo.debug' => false
```

<br/>

## 4. Template usage

Including the script in your template is pretty straightforward:

```php
<?php snippet('matomo'); ?> 
```

<br/>

## 5. Panel setup


#### 5.1. Basic blueprint example

> Please make sure that you have included your `token_auth` in your config.

Adding the panel view is as staightforward as this: 

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
  - width: 1/4
    sections:
      sidebar:
        type: matomo-sidebar
```

#### 5.2. Options

##### Hiding components

There are a bunch of options to help you adjust this default panel view. 
Both sections have three components :

- **The mainview** (`matomo-main`) includes `chart`, `overview` and `widgets`
- **The sidebar** (`matomo-sidebar`) includes `link`, `realtime` and `summary`

You can chose to hide them like this:

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
        chart: false
        overview: false
        widgets: false
  - width: 1/4
    sections:
      sidebar:
        type: matomo-sidebar
        link: false
        realtime: false
        summary: false
```

(Ok, there's nothing left to work with here, but you get the idea).

##### Hiding and sorting widgets

Widgets can be sorted to your taste. You can also choose to display only some of them. Here's a glimpse of the option with all widgets explicitely set, change the order of its list or remove entries to filter widgets.

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
        widgets: 
          - referrerType
          - websites
          - socials
          - devicesType
          - keywords
          - popularPages
```

##### Hiding period ranges

By default, all 4 ranges are displayed (_This year_, _This month_, _Last 7 days_, _Today_).
You can choose to hide some of them by explicitely excluding them from the `periods` list:

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
        periods: 
          - year
          - month
          - week
          - day
```

##### Changing defaults

You have acces to two default options, the `period` and the widgets `limit`.

- `period` is the default active period on first load of the section. It is a string to chose from one of the periods above. Default is `month`.
- `limit` is the number of entries displayed within the widgets. Default is `5`.

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
        defaults:
          period: month
          limit: 5
```

#### 5.3. Complete blueprint example

Here's a glimpse of how the blueprint might look with all options explicitely set:

```yaml
columns:
  - width: 3/4
    sections:
      main:
        type: matomo-main
        chart: true
        overview: true
        defaults:
          period: month
          limit: 5
        periods:
          - year
          - month
          - week
          - day
        widgets:
          - referrerType
          - websites
          - socials
          - devicesType
          - keywords
          - popularPages
  - width: 1/4
    sections:
      sidebar:
        type: matomo-sidebar
        link: true
        realtime: true
        summary: true
```

<br/>

## 6. License

MIT

<br/>

## 7. Credits

Kirby 2 had some Piwik integration plugins:

- [kirby-piwik](https://github.com/schnti/kirby-piwik) by [@schnti](https://github.com/schnti)
- [kirby-analytics](https://github.com/l4ci/kirby-analytics-plugin) by [@l4ci](https://github.com/l4ci)
- [kirby-piwik-widget](https://github.com/mauricerenck/getkirby-piwik-widget) by [@mauricerenck](https://github.com/mauricerenck)
- A promising [Piwik Suite](https://forum.getkirby.com/t/planning-comprehensive-piwik-plugin/954) that sadly has never been released, and was an inspiration to get started on a comprehensive and thorough plugin.

The snippet integration has been shamelessly adapted from [@jenstornell](https://github.com/jenstornell/)'s [kirby-ga](https://github.com/jenstornell/kirby-ga/tree/kirby-3), which has always been a breeze to use 😉

<br/>

## 8. To-do

- [ ] Create a `matomo-page` widget to add on pages, displaying some simple page-related stats
  - [ ] Use the `Actions.getPageUrls` method
  - [ ] Don't filter directly the request with labels as Matomo can store one uri in multiple ways (`fr` or `/fr`, `page` / `/page` or `page?arg=string`)
  - [ ] Propably pass a computed uri as a prop (recreate it from `url` to let custom Page Models do their magic and retrieve the uri matomo sees, when ignoring a subfolder for example)
- [ ] Bulk request widgets
- [ ] Make it responsive
- [ ] Make it lighter
- [ ] Handle API calls errors
- [ ] Keep array maps out of the vue files -> js helper
