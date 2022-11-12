# docu_ce

## About
The extension lists all content elements (and hidden) that are used on the website.
Additional information about the content elements is displayed, as well as information of the cType from page TSconfig.

##
add some documentation

## Installation
Just do
```
composer require magrunert/docu-ce
```

**Set your Page TSconfig globally**

e.g.

```
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    "@import 'EXT:myexample/Configuration/TSconfig/your.tsconfig'"
);
```

## Customization

You can add an external url with further information to the TSconfig page. (e.g. url of your ticket system)

```
mod.wizards.newContentElement.wizardItems.common.elements.[cType].external_url
```