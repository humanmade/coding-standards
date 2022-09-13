# Changelog

## 1.2.0 (September 13, 2022)

- Add new Isset sniff #236
- Update custom escaping functions for clean_html #264
- Require spaces in template strings #256
- Ignore "use" within "class" in OrderSniff #271
- Add dealerdirect/phpcodesniffer-composer-installer to allow-plugins in composer.json #284
- Update PHPCS to support PHP 8+ #282

## 1.1.3 (February 3, 2021)

- Open ESLint peer dependency range to accept ESLint v6 & v7 #222

## 1.1.2 (December 10, 2020)

### Removed:

- Disabled requirement to align PHPDoc parameters, inherited from WordPress-Docs in July's 1.0 release #239

## 1.1.1 (October 27, 2020)

### Added:

- Support Composer 2 #233

### Changed:

- Allowed use of the "relation" element in meta query sniff #232

## 1.1.0 (September 18, 2020)

### Added:

 - Added ESLint `eslint-plugin-import` plugin to enforce consistent ordering of `import` statements in JavaScript module files #219, #84
 - Added ESLint `eslint-plugin-jsdoc` plugin #218
 - Added ESLint `eslint-plugin-sort-destructure-keys` plugin #218

### Changed:

 - Make JSX property sorting case-insensitive #217

##  1.0.0 (July 31, 2020)

### Added:
 - Added `WordPress-Docs` by default in PHPCS #177
 - Added ESLint rule for requiring docblocks #209
 - Added ESLint rule for JSX boolean values #183
 - Added ESLint rule for sorting JSX props #195  
 - Added ESLInt Rules of Hooks ruleset #197
 - Allow `$namespace.php` in function files #99
 - Added Lerna for publishing packages #175

### Updated:
 - Adjust Stylelint class and ID selector patterns #199
 - Updated WPCS to 2.2.1 #151
 - Updated VIPCS to 2.0.0 #151
 - Updated DealerDirect to 0.6 #151
 - Fixed `FunctionCallSignature` inconsistency in phpcbf #200
 - Allow for multiple variable assignments #201
 - Allow for theme filenames when sniffing filename #202
 - Updated `.editorconfig` for YAML & Markdown files #175
 
### Changed:
 - Formatted `package.json` files with tabs #175
 - Moved ESLint `.editorconfig` to project _root_ #175
 - Renamed _root_ `readme.md` to `README.md` #175
 - Updated `composer.json` description #175
 - Updated `package.json` files meta #175

### Removed:
 - Remove `<file>`, `<basepath>` and `testVersion` from ruleset #187, #198

##  0.8.0 (January 29, 2020)

### Added:
 - Added PHPCS Rule to Detect Consecutive Newlines #168
 - Enforce semicolons in JS #169
 - Added `WordPress.Security.EscapeOutput` PHPCS rule #166
 - Added PHPCompatibilityWP standard to PHPCS #81
 - Disallowed usage of `!important` in CSS #164
 - Enforced consistent curly newlines in jsx #172
 - Added `eslint-plugin-sort-destructure-keys` package #179

### Updated:
 - Bumped PHPCS to v3.5 from v3.4 #173
 - Bumped `stylelint-config-wordpress` package to v15 from v13 #165
 - Ignore stylelint `at-rule` line break for `if/else/elseif` #170
 - Restricted fixture tests to load only custom HM sniffs #163

## 0.7.0 (June 5, 2019)

### Changed:
 - Exclude `load.php` from `NamespaceDirectoryNameSniff` #131
 - Allow `json_encode` / `json_decode` function usage #97
 - Fix NamespaceDirectoryNameUnitTest parsing the wrong namespace directory length #140
 - Updated location of stylelint package to reflect correct NPM name #137

### Added:
 - Made PHPCompatibilityWP available via Composer #146

## 0.6.0 (April 2, 2019)

### Summation:
- Updated PHPCS to v3.4 #88
- Updated WPCS to 1.2.0 #82
- Updated eslint to 5.10 and associated deps #101

### Added:
- stylelint configuration #45
- Added VIP PHPCS standards dependency #122

### Changed:
- Use ecmaversion 2018 #87
- Require space in curly braces for React JSX children #121
- Allow multiple declaration in use statement #78
- Exclude the `tests` dir from the NamespaceExclusionTest #112
- Set composer library type to `phpcodesniffer-standard` #116 

### Removed:
- Allow `trigger_error` #98
- Remove assignment of equals rule #96
- Remove `href-no-hash` rule exclusion #114

<details>
    <summary>PHPCS Core Rule Additions</summary>
    
    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases
    * Added new Generic.CodeAnalysis.EmptyPHPStatement sniff
        * Warns when it finds empty PHP open/close tag combinations or superfluous semicolons
    * Added new Generic.Formatting.SpaceBeforeCast sniff
        * Ensures there is exactly 1 space before a type cast, unless the cast statement is indented or multi-line
    * Added new Generic.VersionControl.GitMergeConflict sniff
        * Detects merge conflict artifacts left in files
    * Added Generic.WhiteSpace.IncrementDecrementSpacing sniff
        * Ensures there is no space between the operator and the variable it applies to
    * Added PSR12.Functions.NullableTypeDeclaration sniff
        * Ensures there is no space after the question mark in a nullable type declaration
    * Added new Generic.PHP.LowerCaseType sniff-Ensures PHP types used for type hints, return types, and type casting are lowercase
    * Added new Generic.WhiteSpace.ArbitraryParenthesesSpacing sniff
        * Generates an error for whitespace inside parenthesis that don't belong to a function call/declaration or control structure
        * Generates a warning for any empty parenthesis found
        * Allows the required spacing to be set using the spacing sniff property (default is 0)
        * Allows newlines to be used by setting the ignoreNewlines sniff property (default is false)
    * Added new PSR12.Classes.ClassInstantiation sniff
        * Ensures parenthesis are used when instantiating a new class
    * Added new PSR12.Keywords.ShortFormTypeKeywords sniff
        * Ensures the short form of PHP types is used when type casting
    * Added new PSR12.Namespaces.CompundNamespaceDepth sniff
        * Ensures compound namespace use statements have a max depth of 2 levelsThe max depth can be changed by setting the 'maxDepth' sniff property in a ruleset.xml file
    * Added new PSR12.Operators.OperatorSpacing sniff-Ensures operators are preceded and followed by at least 1 space
</details> 

## 0.5.0 (May 22, 2018)

- Update ESLint config peer dependencies #65
- Add ESLint config test script with example fixtures #42

## 0.4.2 (May 1, 2018)

- Remove support for ESLint-via-phpcs #54
- Ignore array item alignment rule #49
- Ignore line length when checking array alignment #57
- Adjust object rules for destructuring #59

## 0.4.1 (Apr 18, 2018)

- Fix order error for closure `use` #53
- Fix false positives for `T_USE` #12

## 0.4.0 (Apr 17, 2018)

- Always allow spaces inside arrays #3
- Only run PHPCS on PHP files #36
- Enforce spaces inside jsx curly braces #38
- Make index pass its own rules #41
- Add support for a .phpcsignore file #39
- Add Sniff for unused "use" statements #44
- Exclude filesystem groups from checks #50
- Allow inline statements to drop semicolons #51

## 0.3.0 (Jan 18, 2018)

- Update license for new requirements #34
- Add tests for our phpcs sniffs #32
- Update phpcs to v3 #31

## 0.2.2 (Nov 6, 2017)

## 0.2.1 (Dec 8, 2016)

## 0.2.0 (Dec 8, 2016)

## 0.1.0 (Dec 7, 2016)

- Initial Release
