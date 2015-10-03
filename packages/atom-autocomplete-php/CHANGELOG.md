## 0.13.0
* Custom tooltip management (does not rely on atom's one anymore)

## 0.12.0
* Support for {@inheritdoc} && {@inheritDoc} as the only one comment (symfony2 style)
* Bugfixes for non PHP projects
* Bugfixes on Docblock parser

## 0.11.0
* Bugfixes on Goto
* Major refactor in the code of the plugin itself

## 0.10.0
* Autocomplete in catch() {} #91
* Comments "@var $var Class" now supported for completion

## 0.9.0
* Many bugfixes and improvements for tooltips (from @hotoiledgoblinsack)
* Basic autocomplete on "new" keyword (e.g :
    $x = new \DateTime();
    $x->{autocomplete}
)

## 0.8.0
* Tooltips on methods and attributes
* Strikethrough style to deprecated methods

## 0.7.0
* Goto class properties
* Goto bugfixes

## 0.6.0
* Goto command on first level of methods, and classes (#42 by @CWDN)
* Fix namespace on the same line as PHP tag

## 0.5.0
* Support for Windows

## 0.4.0
* Completion on local variables
* Bug fixes

## 0.3.0
* Completion $this on multiline
* Bug fixes

## 0.2.0
* Completion on parent::
* Completion on self::
* Bug fixes

## 0.1.0
* Completion on classNames
* Completion on $this->
* Completion on static methods
* Namespace management
