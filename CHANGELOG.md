## Unreleased (0.6.0)

### Summation:
- Updated PHPCS to v3.4 #88
- Updated WPCS to 1.2.0 #82
- Updated eslint to 5.10 and associated deps #101

### Added:
- Stylelint configuration #45

### Changed:
- Use ecmaversion 2018 #87
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

## 0.4.2 (May 1, 2018)

## 0.4.1 (Apr 18, 2018)

## 0.4.0 (Apr 17, 2018)

## 0.3.0 (Jan 18, 2018)

## 0.2.2 (Nov 6, 2017)

## 0.2.1 (Dec 8, 2016)

## 0.2.0 (Dec 8, 2016)

## 0.1.0 (Dec 7, 2016)
