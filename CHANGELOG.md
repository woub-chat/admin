# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [6.1.0] - 2024-03-30

In this version, a lot was added, a little changed and a little corrected.

### Added
* Laravel 11 support.
* Multi nested for model relation component.
* Image browser for form component.
* Percent input for form component.
* Order by for Select2 load component option.
* Extension provider helpers for extend the core.
* IDE helpers for extensions in navigation.
* Macroable for all default delegates and helper for them.
* Tests.

### Changed
* Download excel and csv notification has been changed.
* Refactor base controller static properties.
* Remove header "Extensions" in the navigation.
* Remove delegates by default.

### Fixed
* Breadcrumbs for the navigation have been fixed.
* Masks for amount and numeric fields have been fixed.
* Fix duplication in form input.
* Access denied for the navigation has been fixed.
* Admin user profile has been fixed.

## [6.0.0] - 2024-03-15

A massive update that includes many changes. 
The approach to the interface has been redesigned, new functions have been added, and errors have been fixed.
Backward compatibility with the fifth version is not broken.

### Added
* Theme support.
* Dockblocks for all classes and methods.
* Type hinting for all classes and methods.
* New component accordion.
* New lang wrapper for fields with translater.
* Use Vue.js in navigation nav bar.

### Changed
* The main javascript file has been reworked, now there is no dependency on the lar/ljs package.
* New architecture of interface components, now all components use the standard Blade template engine instead of lar/taggable, which significantly increases the performance of the panel.
* The navigation core has been redesigned; the lar/roads package is no longer used.
* Removed dependency on the lar/layout package.
* Select2 format for outputting options, now you can use any output format for options, with any model field and/or connection.
* TargetBlank for links, you can now specify in the navigation settings that the link should open in a new tab.
* ImageInput and FileInput can now be used in multi mode with the ability to load multiple files.
* Image modifier for ImageInput, you can now specify an image modifier to be applied to the loaded image. Based on the intervention/image package.
* Artisan commands for creating new controllers, now when specifying a model, the controller fields will be automatically created from the list of model fields, namely from `fillable`.
* Lazy loading for Chart.js, now charts can be loaded after the page is loaded.
* The core is responsible for working with languages, now links are not duplicated.
* Updated Vue support.
* Added support for Alpine.js. All components that were previously in Vue have now been rewritten in Alpine.js. 

### Fixed
* Translations for the interface have been fixed.
* Live and watch zones hash generation has been fixed.
* Backend validation in forms has been fixed.
* Dashboard widgets design has been fixed.
* IDE helper has been fixed.
* NProgress has been fixed.
* Save search request has been fixed.
* Model table formatter `to_json` has been fixed.
* Fixed support Select2 in modal.
* Crypt fields.