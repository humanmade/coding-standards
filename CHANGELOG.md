## Unreleased (0.8.0)

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
    * Added new Generic.PHP.LowerCaseType sniff -Ensures PHP types used for type hints, return types, and type casting are lowercase
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
        * Ensures compound namespace use statements have a max depth of 2 levels The max depth can be changed by setting the 'maxDepth' sniff property in a ruleset.xml file
    * Added new PSR12.Operators.OperatorSpacing sniff -Ensures operators are preceded and followed by at least 1 space
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
