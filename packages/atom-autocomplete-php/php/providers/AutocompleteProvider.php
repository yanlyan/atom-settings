<?php

namespace Peekmo\AtomAutocompletePhp;

class AutocompleteProvider extends Tools implements ProviderInterface
{
    /**
     * Execute the command.
     *
     * @param  array $args Arguments gived to the command.
     *
     * @return array Response
     */
    public function execute($args = array())
    {
        $class = $args[0];
        $name  = $args[1];

        if (strpos($class, '\\') === 0) {
            $class = substr($class, 1);
        }

        $isMethod = false;

        if (strpos($name, '()') !== false) {
            $isMethod = true;
            $name = str_replace('()', '', $name);
        }

        $relevantClass = null;
        $data = $this->getClassMetadata($class);

        if (isset($data['values'][$name])) {
            $memberInfo = $data['values'][$name];

            if (!isset($data['values'][$name]['isMethod'])) {
                foreach ($data['values'][$name] as $value) {
                    if ($value['isMethod'] && $isMethod) {
                        $memberInfo = $value;
                    } elseif (!$value['isMethod'] && !$isMethod) {
                        $memberInfo = $value;
                    }
                }
            }

            $returnValue = $memberInfo['args']['return'];

            if ($returnValue == '$this' || $returnValue == 'static') {
                $relevantClass = $class;
            } elseif ($returnValue === 'self') {
                $relevantClass = $memberInfo['declaringClass'];
            } elseif (ucfirst($returnValue) === $returnValue) {
                // At this point, this could either be a class name relative to the current namespace or a full class
                // name without a leading slash. For example, Foo\Bar could also be relative (e.g. My\Foo\Bar), in which
                // case its absolute path is determined by the namespace and use statements of the file containing it.
                $relevantClass = $returnValue;

                $filename = null;

                try {
                    $reflection = new \ReflectionClass($memberInfo['declaringClass']);

                    $filename = $reflection->getFileName();
                } catch (\Exception $e) {

                }

                if (!empty($returnValue) && $returnValue[0] !== "\\" && $filename) {
                    $parser = new FileParser($filename);

                    $useStatementFound = false;
                    $completedClassName = $parser->getCompleteNamespace($returnValue, $useStatementFound);

                    if ($useStatementFound) {
                        $relevantClass = $completedClassName;
                    } else {
                        $isRelativeClass = true;

                        // Try instantiating the class, e.g. My\Foo\Bar.
                        try {
                            $reflection = new \ReflectionClass($completedClassName);

                            $relevantClass = $completedClassName;
                        } catch (\Exception $e) {
                            // The class, e.g. My\Foo\Bar, didn't exist. We can only assume its an absolute path, using a
                            // namespace set up in composer.json, without a leading slash.
                        }
                    }
                }
            }
        }

        if ($relevantClass) {
            // Minor optimization to avoid fetching the same data twice.
            return ($relevantClass === $class) ? $data : $this->getClassMetadata($relevantClass);
        }

        return array(
            'class'  => null,
            'names'  => array(),
            'values' => array()
        );
    }
}
